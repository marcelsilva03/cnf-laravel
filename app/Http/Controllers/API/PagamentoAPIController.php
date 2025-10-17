<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Efi\Exception\EfiException;
use Efi\EfiPay;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PagamentoAPIController extends Controller
{
    const EFI_CARTAO = 1;
    const EFI_BOLETO = 2;
    const EFI_PIX = 3;
    const BB_PIX_OU_TED = 4;

    protected function apenasDigitos(string $valor): string
    {
        return preg_replace('/\D/', '', $valor);
    }

    protected function formataPrecoComoInteiro($preco): int
    {
        $apenasNumeros = preg_replace('/[^0-9,.]/', '', $preco);
        $precoFloat = (float)str_replace(',', '.', $apenasNumeros);
        $precoInteiro = (int)round($precoFloat, 2);
        return $precoInteiro * 100;
    }

    protected function badRequest($data): string
    {
        $response = [
            'meta' => [
                'code' => 400,
                'message' => 'Requisição mal formulada.',
            ],
            'data' => $data,
            '_links' => [
                'self' => url()->current(),
            ]
        ];
        header('Content-Type: application/json', true, 400);
        return json_encode($response);
    }

    protected function falhaNoPagamento($data): string
    {
        $message = 'Falha no pagamento.';
        if(array_key_exists("message", $data)){
              $message = $data["message"];
        }
        $response = [
            'meta' => [
                'code' => 500,
                'message' => $message,
            ],
            'data' => $data,
            '_links' => [
                'self' => url()->current(),
            ]
        ];
        header('Content-Type: application/json', true, 500);
        return json_encode($response);
    }

    protected function respostaBemSucedida($data): string
    {
        $res = [
            'meta' => [
                'code' => 200,
                'message' => 'Requisição bem sucedida.',
            ],
            'data' => $data,
            '_links' => [
                'self' => url()->current(),
            ]
        ];
        header('Content-Type: application/json', true, 200);
        return json_encode($res);
    }

    protected function defineFormaDePagamentoSolicitacao(string $codigoSolicitacao, int $metodoDePagamento, string $tokenDaTransacao = ''): bool
    {
        $solicitacao = Solicitacao::where('pag_code', $codigoSolicitacao)->first();
        if ($solicitacao) {
            $solicitacao->pag_metodo_escolhido = $metodoDePagamento;
            if (!empty($tokenDaTransacao)) {
                $solicitacao->pag_token_transacao = $tokenDaTransacao;
            }
            return $solicitacao->save();
        }
        return false;
    }

    protected function obtemCorpoRequisicaoEFI(array $dados, string $tipo): array
    {
        $items = [
            [
                'name'   => $dados['produto'],
                'amount' => 1,
                'value'  => $this->formataPrecoComoInteiro($dados['valor']),
            ]
        ];

        // URL única; se quiser separar por meio, ajuste aqui.
        $notificationUrl = $tipo === 'cartao'
        ? url('/api/pagamentos/efi/notificacao-cartao-boleto')
        : url('/api/pagamentos/efi/notificacao-cartao-boleto');

        $metadata = [
            'custom_id'        => $dados['codigo'],
            'notification_url' => $notificationUrl,
        ];

        // Nome base para o pagador
        $customerName = $tipo === 'cartao'
        ? ($dados['nometitular']     ?? $dados['nomeSolicitante'] ?? '')
        : ($dados['nomeSolicitante'] ?? $dados['nometitular']     ?? '');

        $customerName = $tipo === 'boleto'
        ? ($dados['nomeboleto']     ?? $dados['nomeSolicitante'] ?? '')
        : ($dados['nomeSolicitante'] ?? $dados['nomeboleto']     ?? '');

        // Documento pode vir como cpfcnpj (preferido) ou cpf (legado)
        $documento = $this->apenasDigitos($dados['cpfcnpj'] ?? ($dados['cpf'] ?? ''));

        // Monta customer conforme PF/PJ
        $customer = [
            'name'         => $customerName,
            'email'        => $dados['email']        ?? null,
            'phone_number' => $this->apenasDigitos($dados['telefone'] ?? ''),
        ];

        $lenDoc = strlen($documento);
        if ($lenDoc === 11) {
            // Pessoa Física
            $customer['cpf'] = $documento;
        } elseif ($lenDoc === 14) {
            // Pessoa Jurídica
            $customer['juridical_person'] = [
                'corporate_name' => $dados['razaosocial'] ?? $customerName, // usa "Nome do titular" ou "nomeboleto" como fallback
                'cnpj'           => $documento,
            ];
            // Não envie 'cpf' quando for PJ.
        } else {
            // Se quiser, lance exceção ou trate com erro mais amigável
            throw new \InvalidArgumentException('Documento do pagador inválido (use CPF com 11 dígitos ou CNPJ com 14).');
        }

        // remove nulos/vazios
        $customer = array_filter($customer, fn ($v) => !($v === null || $v === ''));

        // PAYMENT
        $payment = [];

        if ($tipo === 'cartao') {
            $numeroDeParcelas = intval($dados['numeroparcelas']);
            $credit_card = [
                'customer'      => $customer,
                'installments'  => $numeroDeParcelas,
                'payment_token' => $dados['tokenpagamento'],
            ];

            // Regra de desconto à vista
            if ($numeroDeParcelas === 1) {
                $credit_card['discount'] = [
                    'type'  => 'percentage',
                    'value' => 500, // 5%
                ];
            }

            $payment['credit_card'] = $credit_card;
        }

        if ($tipo === 'boleto') {
            $banking_billet = [
                'expire_at' => (new \DateTime('+2 days'))->format('Y-m-d'),
                'customer'  => $customer,
                'discount'  => [
                'type'  => 'percentage',
                'value' => 1000, // 10%
                ],
            ];
            $payment['banking_billet'] = $banking_billet;
        }

        return [
            'items'    => $items,
            'metadata' => $metadata,
            'payment'  => $payment,
        ];  
    }

    protected function obtemRespostaEFI(array $params, array $body): array
    {
        try {
            $efiApi = new EfiPay(config('efipay.options'));
            $response = $efiApi->createOneStepCharge($params, $body);
            return json_decode(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), true);
        } catch (EfiException $e) {
            return [
                'code' => $e->code,
                'erro' => $e->error,
                'message' => $e->errorDescription
            ];
        }

    }

    public function PagamentoEFICartao(Request $request): string
    {
        $requerimentos = [
            'codigo'          => 'required',
            'produto'         => 'required',
            'valor'           => 'required',
            'nomeSolicitante' => 'required|string',
            'telefone'        => 'required',
            'email'           => 'required|email',
            'cpfcnpj'         => 'required',          
            'nometitular'     => 'required|string',
            'numeroparcelas'  => 'required|integer',
            'tokenpagamento'  => 'required',
        ];
        $payload = $request->json()->all();
        $validation = Validator::make($payload, $requerimentos);
        if ($validation->fails()) {
            return $this->badRequest($validation->errors());
        }
        $body = $this->obtemCorpoRequisicaoEFI($payload, 'cartao');
        $response = $this->obtemRespostaEFI([], $body);
        if (isset($response['erro']))  {
            return $this->falhaNoPagamento($response);
        }
        if (isset($response["data"]["status"] ) && $response["data"]["status"] == "unpaid") {
            return $this->falhaNoPagamento(['message' => $response["data"]['refusal']['reason']]);
        }

        $efiData = $response['data'];
        $successo = $this->defineFormaDePagamentoSolicitacao($payload['codigo'], self::EFI_CARTAO, strval($efiData['charge_id']));
        if (!$successo) {
            return $this->falhaNoPagamento(['message' => 'Falha ao atualizar dados da solicitação.']);
        }
        $cartao = [
            'status' => $efiData['status'],
            'charge_id' => $efiData['charge_id'],
            'codigo' => $payload['codigo'],
        ];
        return $this->respostaBemSucedida($cartao);
    }

    public function PagamentoEFIBoleto(Request $request): string
    {
        $requerimentos = [
            'codigo' => 'required',
            'produto' => 'required',
            'valor' => 'required',
            'nomeboleto' => 'required',
            'cpfcnpj' => 'required',
        ];
        $payload = $request->json()->all();
        $validation = Validator::make($payload, $requerimentos);
        if ($validation->fails()) {
            return $this->badRequest($validation->errors());
        }
        $body = $this->obtemCorpoRequisicaoEFI($payload, 'boleto');
        $response = $this->obtemRespostaEFI([], $body);
        if (isset($response['erro'])) {
            $this->falhaNoPagamento($response);
        }
        /**
         * barcode: string "codigo de barras numérico"
         * billet_link: string "link gerencianet para visualização do boleto bancário"
         * charge_id: int
         * expire_at: string "Y-m-d"
         * link: string "link gerencianet com visualização do boleto em opções para download e impressão"
         * payment: string "banking_billet"
         * pdf: [
         *      charge: string "link gerencianet em formato PDF"
         * ]
         * pix: [
         *      qrcode: string "qrcode copia e cola"
         *      qrcode_image: string "data:image/svg+xml;base64,..."
         * ]
         * status: string "waiting|..."
         * total: integer "valor do boleto"
         */
        $efiData = $response['data'];
        $successo = $this->defineFormaDePagamentoSolicitacao($payload['codigo'], self::EFI_BOLETO, strval($efiData['charge_id']));
        if (!$successo) {
            return $this->falhaNoPagamento(['message' => 'Falha ao atualizar dados da solicitação.']);
        }
        $boleto = [
            'codigo_de_barras' => $efiData['barcode'],
            'pdf' => $efiData['pdf']['charge']
        ];
        return $this->respostaBemSucedida($boleto);
    }

    public function PagamentoEFIPix(Request $request): string
    {
        $requerimentos = [
            'codigo' => 'required',
            'produto' => 'required',
            'valor' => 'required',
            'nomeSolicitante' => 'required',
        ];
        $dados = $request->json()->all();
        $validation = Validator::make($dados, $requerimentos);

        if ($validation->fails()) {
            return $this->badRequest($validation->errors());
        }

        if (env('EFI_ENV')=='prod') {
            $preco = number_format($this->formataPrecoComoInteiro($dados['valor']) / 100, 2, '.', '');
            $preco = number_format($preco * 0.9, 2, '.', '');
        } else {
            // Teste de webhook EFI:preço fixo de R$ 1,00
            $preco = number_format(1, 2, '.', '');
}
        $txid = str_replace('-', '', $dados['codigo']);
        $params = ['txid' => $txid];

        $body = [
            'calendario' => [
                'expiracao' => 3600 // 1 hora em segundos
            ],
            'valor' => [
                'original' => $preco
            ],
            'chave'               => env('EFI_CHAVE_PIX'),
            'solicitacaoPagador'  => $dados['nomeSolicitante'],
            'infoAdicionais'      => [[
                'nome'  => 'Produto',
                'valor' => $dados['produto']
            ]]
        ];

        //$options = config('efipay.options');

        try {
            $api = new EfiPay(config('efipay.options'));
            $pix = $api->pixCreateCharge($params, $body);

            if ($pix['txid']) {
                $params = [
                    'id' => $pix['loc']['id']
                ];

                try {
                    $qrcode = $api->pixGenerateQRCode($params);
                    if ($this->defineFormaDePagamentoSolicitacao($dados['codigo'], self::EFI_PIX, $txid)) {
                        return $this->respostaBemSucedida($qrcode);
                    }
                    return $this->falhaNoPagamento([
                        'message' => 'Falha inesperada ao armazenar dados de pagamento da solicitação.'
                    ]);
                } catch (EfiException $e) {
                    return $this->falhaNoPagamento([
                        'code' => $e->code,
                        'erro' => $e->error,
                        'message' => $e->errorDescription
                    ]);
                } catch (Exception $e) {
                    return $this->falhaNoPagamento([
                        'code' => $e->getCode(),
                        'trace' => $e->getTrace(),
                        'message' => $e->getMessage()
                    ]);
                }
            } else {
                return $this->falhaNoPagamento([
                    'message' => 'Atributo txid não está presente.'
                ]);
            }
        } catch (EfiException $e) {
            return $this->falhaNoPagamento([
                'code' => $e->code,
                'erro' => $e->error,
                'message' => $e->errorDescription
            ]);
        } catch (Exception $e) {
            return $this->falhaNoPagamento([
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public function PagamentoBB(Request $request): string
    {
        $requerimentos = [
            'codigo' => 'required',
        ];
        $dados = $request->json()->all();
        $validation = Validator::make($dados, $requerimentos);
        if ($validation->fails()) {
            return $this->badRequest($validation->errors());
        }
        if ($this->defineFormaDePagamentoSolicitacao($dados['codigo'], self::BB_PIX_OU_TED)) {
            $email = config('constants.emails')['financeiro'];
            return $this->respostaBemSucedida(['message' => "Confirmado pagamento via TED ou PIX para Banco do Brasil. Envie o comprovante para o email $email"]);
        }
        return $this->falhaNoPagamento(['message' => 'Falha inesperada ao armazenar dados de pagamento da solicitação.']);
    }

    public function NotificacaoTransacao(): JsonResponse
    {
        $payload = [
            'meta' => [
                'code'    => 200,
                'message' => 'Requisição bem sucedida.',
            ],
            'data' => config('efipay.options'),
        ];

        return response()->json(
            $payload,
            200,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function NotificacaoCartao(): JsonResponse
    {
        $payload = [
            'meta' => [
                'code'    => 200,
                'message' => 'Requisição bem sucedida.',
            ],
            'data' => config('efipay.options'),
        ];

        return response()->json(
            $payload,
            200,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function notificacaoCartaoBoleto(Request $request): JsonResponse
    {
        // Recebe o token
        $token = $request->input('notification');

        // Configurações do config/efipay.php
        $options = config('efipay.options');

        // Instancia o cliente EfiPay
        $efi = new \Efi\EfiPay($options);

        // Busca a notificação
        try {
            $notificacao = $efi->getNotification(
                ['token' => $token],
                []
            );
        } catch (EfiException $e) {
            return response()->json([
                'error' => 'Falha ao obter notificação: ' . $e->getMessage()
            ], 500);
        }

        $events = $notificacao['data'] ?? [];
        if (empty($events)) {
            return response()->json([
                'error' => 'Nenhuma notificação disponível.'
            ], 400);
        }

        // escolhe o último evento (mais recente)
        $event = end($events);

        $code      = $event['custom_id'];
        $statusKey = $event['status']['current'];
        $chargeId  = $event['identifiers']['charge_id'];
        $createdAt = $event['created_at'];


        // Mapeia status EFI → PagSeguro
        $mapStatus = [
            'new'        => 29,
            'waiting'    => 1,
            'identified' => 3,
            'approved'   => 3,
            'paid'       => 4,
            'unpaid'     => 7,
            'refunded'   => 6,
            'contested'  => 5,
            'canceled'   => 7,
            'settled'    => 4,
            'link'       => 29,
            'expired'    => 7,
        ];

        $status    = $mapStatus[$statusKey] ?? null;

        // Busca a Solicitação no banco
        /** @var Solicitacoes|null $sol */
        $sol = Solicitacao::where('pag_code', $code)->first();
        if (! $sol) {
            return response()->json([
                'error' => 'Solicitação não encontrada para o código informado.'
            ], 404);
        }

        // Atualiza campos e salva
        $sol->sol_status = $status;
        $sol->pag_token_transacao = $chargeId;
        $sol->pag_date  = $createdAt;
        $sol->save();

        // Se pagamento concluído (status 4), dispara e-mails
        if ($status === 4) {
            // Extrai “cidade” e “UF” de sol_estado_cidade (formato “Cidade/UF” ou “/UF” ou “”)
            $localStr = $sol->sol_estado_cidade;
            if (! empty($localStr)) {
                // explode vai gerar até 2 elementos: [0]=cidade (pode ficar vazio), [1]=UF
                [$cidade, $uf] = array_pad(explode('/', $localStr, 2), 2, '');
                $cidade = trim($cidade);
                $uf     = trim($uf);
            } else {
                $cidade = $uf = '';
            }

            $local = (object)[
                'ecd_nome'  => $cidade,
                'ees_sigla' => $uf,
            ];

            // Formata datas de nascimento e óbito (colunas em "YYYY-MM-DD")
            $nasc = $sol->sol_data_nascimento
                ? Carbon::createFromFormat('Y-m-d', $sol->sol_data_nascimento)
                        ->format('d/m/Y')
                : null;

            $obito = $sol->sol_data_obito
                ? Carbon::createFromFormat('Y-m-d', $sol->sol_data_obito)
                        ->format('d/m/Y')
                : null;

            // E-mail para equipe de pesquisa
            Mail::send(
                'emails.solicitacao.solicitacao_pesquisa_equipe',
                compact('sol', 'local', 'nasc', 'obito'),
                function ($m) use ($sol) {
                    $m->to('pesquisa@falecidosnobrasil.org.br')
                      ->subject("Pesquisa nº: {$sol->sol_id} — Falecido: {$sol->sol_nome_fal}");
                }
            );

            // E-mail para o solicitante
            Mail::send(
                'emails.solicitacao.solicitacao_pesquisa_usuario',
                compact('sol', 'local', 'nasc', 'obito'),
                function ($m) use ($sol) {
                    $m->to($sol->sol_email_sol)
                      ->subject("Solicitação de Pesquisa nº: {$sol->sol_id} — Falecido: {$sol->sol_nome_fal}");
                }
            );
        }

        // Retorna sucesso à EFI
        return response()->json([
            'meta' => [
                'code'    => 200,
                'message' => 'Notificação processada com sucesso.'
            ]
        ], 200);
    }

    public function streamPixStatus(string $code): StreamedResponse
    {
        return new StreamedResponse(function () use ($code) {
            // Cabeçalhos SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache, no-transform');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no'); // Nginx

            // flush inicial para “abrir” o stream
            echo ": open\n\n";
            @ob_flush(); @flush();

            $start = time();

            while (time() - $start < 60) { // 60s por conexão
                // ❗ Model certo (singular) + coluna certa (pag_code)
                $sol = Solicitacao::where('pag_code', $code)->first();

                // ❗ Condição de "pago" alinhada ao que seu webhook atualiza
                // Seu webhook seta sol_status=4 e pag_date (Y-m-d H:i:s)
                $solStatus = (int) ($sol->sol_status ?? -1);
                $pagDate   = $sol->pag_date ?? null;

                $pago = $sol && (
                    in_array($solStatus, [3, 4], true) // 3 = PAGA, 4 = LIBERADA
                    || !empty($pagDate)
                );

                if ($pago) {
                    echo "event: pago\n";
                    echo 'data: ' . json_encode([
                        'paid'    => true,
                        'status'  => $sol->sol_status,
                        'paid_at' => $pagDate,         // vai no seu mostrarPago()
                        'code'    => $code,
                    ]) . "\n\n";
                    @ob_flush(); @flush();
                    break;
                }

                // ping keep-alive a cada 2s
                echo "event: ping\n";
                echo "data: ok\n\n";
                @ob_flush(); @flush();
                sleep(2);
            }
        });
    }

    public function pixNotification(Request $request)
    {
        // Vai logar todo SELECT/UPDATE
        /*DB::listen(function($q) {
            Log::info('SQL Pix', ['sql'=>$q->sql,'bind'=>$q->bindings]);
        });*/

        //Log::info('Webhook Pix Notification EFIPAY:', $request->all());

        $pix     = $request->input('pix.0', []);
        $txid    = $pix['txid']    ?? null;
        $horario = $pix['horario'] ?? null;

        //Log::info('pixNotification: extraído txid e horario', compact('txid','horario'));

        if (! $txid) {
            Log::warning('pixNotification: txid ausente no payload', ['payload' => $pix]);
            return response()->json(['received' => false, 'error' => 'txid ausente'], 400);
        }

        $sol = Solicitacao::where('pag_token_transacao', $txid)->first();
        if (! $sol) {
            Log::warning('pixNotification: registro não encontrado', ['pag_token_transacao' => $txid]);
            return response()->json(['received' => false, 'error' => 'solicitacao não encontrada'], 404);
        }

        // Formata a data com fuso de SP
        $pagDate = Carbon::parse($horario)
                             ->setTimezone('America/Sao_Paulo')
                             ->format('Y-m-d H:i:s');

        /*Log::info('pixNotification: pronto para atualizar', [
            'id'          => $sol->sol_id,
            'sol_status'  => 4,
            'pag_date'    => $pagDate,
        ]);*/

        // Faz o update
        $sol->update([
            'sol_status'   => 4,
            'pag_date' => $pagDate,
        ]);

        // Prepara dados para o e-mail
        // Extrai cidade e UF de sol_estado_cidade
        [$cidade, $uf] = array_pad(
            explode('/', $sol->sol_estado_cidade ?? '' , 2),
            2,
            ''
        );
        $cidade = trim($cidade);
        $uf     = trim($uf);

        $local = (object)[
            'ecd_nome'  => $cidade,
            'ees_sigla' => $uf,
        ];

        // Formata datas de nascimento e óbito
        $nasc = $sol->sol_data_nascimento
            ? Carbon::createFromFormat('Y-m-d', $sol->sol_data_nascimento)
                    ->format('d/m/Y')
            : null;

        $obito = $sol->sol_data_obito
            ? Carbon::createFromFormat('Y-m-d', $sol->sol_data_obito)
                    ->format('d/m/Y')
            : null;

        // Dispara e-mail para a equipe
        Mail::send(
            'emails.solicitacao.solicitacao_pesquisa_equipe',
            compact('sol', 'local', 'nasc', 'obito'),
            function ($m) use ($sol) {
                $m->to('pesquisa@falecidosnobrasil.org.br')
                  ->subject("Pesquisa nº: {$sol->sol_id} — Falecido: {$sol->sol_nome_fal}");
            }
        );

        // Dispara e-mail para o solicitante
        Mail::send(
            'emails.solicitacao.solicitacao_pesquisa_usuario',
            compact('sol', 'local', 'nasc', 'obito'),
            function ($m) use ($sol) {
                $m->to($sol->sol_email_sol)
                  ->subject("Solicitação de Pesquisa nº: {$sol->sol_id} — Falecido: {$sol->sol_nome_fal}");
            }
        );

        return response()->json(['received' => true], 200);
    }
}
