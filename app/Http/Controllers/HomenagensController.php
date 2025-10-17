<?php

namespace App\Http\Controllers;

use App\Enums\Parentesco;
use App\Jobs\ReportaTransacaoViaEmail;
use App\Models\Falecido;
use App\Models\Homenagem;
use App\Models\User;
use App\Services\LocalidadesService;
use App\Traits\HasCustomPagination;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class HomenagensController extends Controller
{
    use HasCustomPagination;

    const REQUIRED_FIELDS = [
        'uuid' => 'required|uuid',
        'email' => 'required|email',
        'nome_autor' => 'required|string|min:3',
        'cpf_autor' => 'required|string',
        'whatsapp' => 'required|string',
        'parentesco' => 'required|integer',
        'opcaoImagemFundo' => 'required|string',
        'homenagem' => 'required|string',
        'fotofalecido' => 'required|file|image|max:2048',
    ];
    protected LocalidadesService $localidades;

    public function __construct(LocalidadesService $localidadesService)
    {
        //pre carrega estados e cidades.
        $this->localidades = $localidadesService;
    }

    private function obterCamposNecessarios(array $pessoaCompleta): array
    {
        $dadosNecessarios = [
            'fal_data_nascimento',
            'fal_data_falecimento',
            'fal_uuid',
            'fal_nome',
            'fal_doc',
        ];
        $pessoa = [];
        foreach ($pessoaCompleta as $chave => $valor) {
            if (in_array($chave, $dadosNecessarios)) {
                $pessoa[$chave] = $valor;
            }
        }
        return $pessoa;
    }

    private function removeIDs(array $colecaoComIds, $prefixo = 'hom_'): array
    {
        foreach ($colecaoComIds as $colecao) {
            unset($colecao[$prefixo . 'id']);
        }
        return $colecaoComIds;
    }

    private function obterOpcoesDeImagemDeFundo(): array
    {
        $diretorioBase = config('paths.imagensDeFundo');
        $diretorio = public_path($diretorioBase);
        $arquivos = File::files($diretorio);
        $imagens = [];
        foreach ($arquivos as $arquivo) {
            $imagens[] = $diretorioBase . '/' . $arquivo->getFilename();
        }
        return $imagens;
    }

    public function index(): View|Factory|Application
    {
        $homenagens = Homenagem::all();
        return view('homenagemRecentes')->with('homenagens', $homenagens);
    }

    public function buscar(Request $request): View|Factory|Application|RedirectResponse
    {
        $nome = $request->query('nome');
        if (empty($nome)) {
            return redirect()->to('/homenagens');
        }

        $estado = $request->query('estado');
        $cidade = $request->query('cidade');
        $nomeExato = $request->filled('nome-exato');
        if ($nomeExato) {
            $simboloDeComparacao = '=';
            $stringParaComparar = "$nome";
        } else {
            $simboloDeComparacao = 'like';
            $stringParaComparar = "%$nome%";
        }

        $perPage = $request->query('paginacao', 10);
        $endpoint = $request->url();
        $url = "$endpoint?nome=$nome";

        $pessoas = Falecido::where('fal_nome', $simboloDeComparacao, $stringParaComparar);
        if (!empty($estado)) {
            $pessoas = $pessoas->where('fal_uf', '=', $estado);
            $url .= "&estado=$estado";
        }
        if (!empty($cidade)) {
            $pessoas = $pessoas->where('fal_cidade', '=', $cidade);
            $url .= "&cidade=$cidade";
        }
        $pessoas = $pessoas->where('fal_status', Falecido::STATUS['ATIVO']);
        $data = $this->splitDataAndPagination($pessoas, $perPage, $url);

        $ufs = $this->localidades->obterSiglasDosEstados();
        return view('homenagemResultados')
            ->with([
                'nome' => $nome,
                'estado' => $estado,
                'cidade' => $cidade,
                'resultados' => $data['data'],
                'paginacao' => $data['pagination']
            ])
            ->with('ufs', $ufs);
    }

    public function registrar(Request $request): View|Factory|Application|RedirectResponse
    {
        $uuid = $request->query('uuid');
        $pessoa = Falecido::where('fal_uuid', '=', $uuid)->first();
        if (!$pessoa) {
            return redirect()->to('/homenagens');
        } else {
            $falecido = $pessoa->toArray();
        }
        $falecido['url'] = "/homenagens/$uuid";
        $parentescos = Parentesco::toArray();
        $opcoesDeImagemDeFundo = $this->obterOpcoesDeImagemDeFundo();
        $solicitante = [];
        if (auth()->check()) {
            $user = auth()->user();
            $user->load('perfil');
            $user->load('role');
            $solicitante = $user->toArray();
        }
        return view('homenagemNova')
            ->with(compact('falecido'))
            ->with(compact('solicitante'))
            ->with(compact('parentescos'))
            ->with('opcoesImagem', $opcoesDeImagemDeFundo);
    }

    public function porUUID($uuid): View|Factory|Application|RedirectResponse
    {
        if (empty($uuid)) {
            return redirect()->to('/homenagens');
        }
        $pessoa = Falecido::where('fal_uuid', $uuid)
            ->where('fal_status', Falecido::STATUS['ATIVO'])
            ->first();
        if (!$pessoa) {
            // pensar neste caso
            // usuario tem acesso a um UUID e o falecido está INATIVO
            // retornar com erro 404 na notificação
            return redirect()->to('/homenagens');
        }

        $objetoHomenagens = Homenagem::where('hom_id_falecido', $pessoa->fal_id)
            ->where('hom_status', Homenagem::STATUS['PUBLICADO'])
            ->get();


        $homenagens = array_map(function ($homenagem) {
            // Verifique se a chave 'hom_parentesco' existe em Parentesco::toArray()
            $parentescoArray = Parentesco::toArray();

            // Verifique se a chave do parentesco existe no array
            $homenagem['hom_parentesco'] = $parentescoArray[$homenagem['hom_parentesco']] ?? 'Valor não encontrado'; // Caso não exista, atribui um valor padrão

            return $homenagem;
        }, $objetoHomenagens->toArray());


        $homenagens = $this->removeIDs($homenagens);
        $falecido = $this->obterCamposNecessarios($pessoa->toArray());
        $falecido['fal_data_nascimento'] = Carbon::createFromFormat(
            'Y-m-d',
            $falecido['fal_data_nascimento'])
            ->format('d/m/Y');
        $falecido['fal_data_falecimento'] = Carbon::createFromFormat(
            'Y-m-d',
            $falecido['fal_data_falecimento'])
            ->format('d/m/Y');


        return view('homenagemFalecido')
            ->with(compact('falecido'))
            ->with(compact('homenagens'));
    }

    public function detalhesHomenagem(Request $request): View|Factory|Application
    {
        $uuid = $request->route('uuid');
        $code = $request->route('code');

        if (empty($uuid) || empty($code)) {
            return redirect()->back()->with('notificacao', [
                'mensagem' => 'UUID ou código não fornecido.',
                'tipo' => 'erro'
            ]);
        }

        $homenagem = Homenagem::where('hom_codigo', $code)->first();
        $falecido = Falecido::where('fal_uuid', $uuid)->first();

        if (!$homenagem || !$falecido) {
            return redirect()->back()->with('notificacao', [
                'mensagem' => 'Homenagem ou falecido não encontrado.',
                'tipo' => 'erro'
            ]);
        }

        $homenagem = $homenagem->toArray();
        $homenagem['hom_parentesco'] = Parentesco::toArray()[$homenagem['hom_parentesco']];
        $homenagens = [$homenagem];
        $falecido = $falecido->toArray();
        return view('homenagemFalecido')
            ->with(compact('falecido'))
            ->with(compact('homenagens'));
    }


    public function enviarEmailTransacional(string $emailDestinatario, string $assunto, string $templateView, array $dadosEmail): bool
    {
        try {
            $configMail = config('constants.emails');
            $destinatarios = $configMail['destinatarios'];

            $dadosEmail = [
                'envelope' => [
                    'to' => $emailDestinatario,
                    'assunto' => $assunto,
                    'cc' => $destinatarios['admin'],
                    'bcc' => $destinatarios['dev']
                ],
                'template' => [
                    'view' => $templateView,
                    'dados' => $dadosEmail
                ]
            ];

            ReportaTransacaoViaEmail::dispatch($dadosEmail);

            return true;
        } catch (\Exception $e) {
            // Log exception or handle error
            Log::error('Erro ao enviar email transacional: ' . $e->getMessage());
            return false;
        }
    }

    public function recebeRegistro(Request $request): RedirectResponse
    {
        Log::info('Entrou na função => recebeRegistro', $request->all());
        // Verifique as permissões antes de tentar o upload
        if (!$this->checkUploadDirectoryPermissions()) {
            Log::info('Entrou no IF => !$this->checkUploadDirectoryPermissions()');
            // Se as permissões estiverem faltando, envie uma mensagem de erro
            return redirect()->back()->with('error', 'O diretório de upload não tem permissões adequadas. Por favor, verifique as permissões do diretório "images/users-upload".');
        }

        try {
            $request->validate(self::REQUIRED_FIELDS);
            Log::info('Validou os campos obrigatórios !');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $responseMessage = !empty($errors) ? $errors[0] : 'Erro de validação desconhecido.';
            $detailedErrors = $e->validator->errors()->messages();
            $responseMessage = !empty($detailedErrors)
                ? collect($detailedErrors)->map(function ($messages, $field) {
                    return "{$field}: " . implode(', ', $messages);
                })->implode(' | ')
                : 'Erro de validação desconhecido.';
            $cpf = $this->sanitizeToDigits($request->cpf_autor);
            $whatsapp = $this->sanitizeToDigits($request->whatsapp);
            $usuario = $this->handleUser($request->email, $cpf, $whatsapp, $request->nome_autor);
            $this->sendNotification('erro', $responseMessage, $usuario);
            return redirect()->back()->withInput();
        }

        $uuid = $request->uuid;
        Log::info('Atribuiu para a variável uuid o valor da requisição');
        $cpf = $this->sanitizeToDigits($request->cpf_autor);
        Log::info('Atribuiu para a variável cpf o valor da requisição');
        $whatsapp = $this->sanitizeToDigits($request->whatsapp);
        Log::info('Atribuiu para a variável whatsapp o valor da requisição');

        // Processar a foto do falecido (upload)
        $urlFoto = $this->processPhotoUpload($request->file('fotofalecido'), $uuid);

        // Foto de fundo (campo hidden com a URL da imagem já existente)
        $urlFotoFundo = $request->input('opcaoImagemFundo'); // Campo que armazena a URL da imagem de fundo

        // Definindo uma imagem padrão
        if (!$urlFotoFundo) {
            $urlFotoFundo = 'images/fundo-homenagens/peace1.jpg';
        }

        // Encontrar o falecido
        $falecido = Falecido::where('fal_uuid', $uuid)->first();
        $usuario = $this->handleUser($request->email, $cpf, $whatsapp, $request->nome_autor);

        // Gerar os dados para salvar a homenagem
        $dadosHomenagem = $this->buildHomenagemData($request, $falecido, $cpf, $whatsapp, $usuario, $urlFoto, $urlFotoFundo);

        // Salvar a homenagem
        $homenagem = $this->handleHomenagem($dadosHomenagem);

        // Enviar notificação de sucesso ou erro
        $this->sendNotification(
            $homenagem ? 'sucesso' : 'erro',
            $homenagem
                ? 'Homenagem registrada com sucesso! Aguardando aprovação da moderação para ser publicada.'
                : 'Falha ao registrar homenagem...',
            $usuario
        );

        return $homenagem ? redirect()->to('/') : redirect()->back()->withInput();
    }

    private function sanitizeToDigits(mixed $cpf_autor): array|string|null
    {
        return preg_replace('/\D/', '', $cpf_autor);
    }

    private function processPhotoUpload(array|UploadedFile|null $file, mixed $uuid): ?string
    {
        if (empty($file)) {
            return null;
        }

        // Validar que o arquivo é uma imagem
        if (!$file->isValid() || !in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
            return null;
        }

        // Gerar nome do arquivo a partir do UUID
        $nomeArquivo = $uuid . '.jpg';

        //$path = 'public/images/users-upload'; // Diretório público para salvar as imagens de falecido
        $path = config('paths.fotosFalecido');

        // Armazenar o arquivo no diretório e gerar o caminho
        $file->storeAs($path, $nomeArquivo);
        Log::info('Imagem do falecido => ' . $path . '/'. $nomeArquivo);

        // Retornar o caminho para a foto salva
        return 'storage/users-upload/' . $nomeArquivo;
    }

    private function sendNotification(string $tipo, string $mensagem, User $user): void
    {
        session()->flash('notificacao', [
            'mensagem' => $mensagem,
            'tipo' => $tipo === 'sucesso' ? 'sucesso' : 'erro'
        ]);
        if ($tipo === 'sucesso') {
            $configMail = config('constants.emails');
            $destinatarios = $configMail['destinatarios'];
            $dadosEmail = [
                'envelope' => [
                    'to' => $user->email,
                    'assunto' => 'Assunto do email',
                    'cc' => $destinatarios['admin'],
                    'bcc' => $destinatarios['dev']
                ],
                'template' => [
                    'view' => $configMail['templates']['homenagem.recebida'],
                    'dados' => [
                        'nome' => $user->name,
                        'mensagem' => $mensagem,
                    ]
                ]
            ];
            ReportaTransacaoViaEmail::dispatch($dadosEmail);
        }
    }

    private function buildHomenagemData(Request $request, $falecido, $cpf, $whatsapp, $usuario, $urlFoto, $urlFotoFundo = null): array
    {
        return [
            'id_falecido' => $falecido->fal_id,
            'uuid_falecido' => $falecido->fal_uuid ?? '',
            'nome_autor' => $usuario->name ?? '',
            'cpf_autor' => $cpf ?? '',
            'url_foto' => $urlFoto,
            'url_fundo' => $urlFotoFundo ?? $request->opcaoImagemFundo ?? '',
            'mensagem' => $request->homenagem ?? '',
            'whatsapp' => $whatsapp ?? '',
            'email' => $usuario->email ?? '',
            'parentesco' => $request->parentesco ?? '',
        ];
    }

    private function handleUser(string $email, string $cpf, string $whatsapp, string $nome_autor): User
    {
        $mustSave = false;

        // Verifica se o usuário já existe
        $usuario = User::where('email', $email)->first();

        if ($usuario) {
            $usuario->load('perfil', 'role'); // Carregando as relações

            // Verifica se o perfil existe
            if (!$usuario->perfil) {
                // Cria o perfil se não existir
                $usuario->perfil()->create([
                    'cpf' => $cpf,
                    'fone_numero' => $whatsapp,
                ]);
                $mustSave = true;
            } else {
                // Atualiza os dados do perfil usando update()
                if (empty($usuario->perfil->cpf)) {
                    $usuario->perfil()->update(['cpf' => $cpf]); // Usa update
                    $mustSave = true;
                }
                if (empty($usuario->perfil->fone_numero)) {
                    $usuario->perfil()->update(['fone_numero' => $whatsapp]); // Usa update
                    $mustSave = true;
                }
            }
        } else {
            // Usuário não existe, criar novo
            $usuario = new User();
            $senha = $usuario->registroPrevio(['nome' => $nome_autor, 'email' => $email]);
            // $senha deve ir pro email
            $usuario->save();

            // Criar o perfil associado ao novo usuário
            $usuario->perfil()->create([
                'cpf' => $cpf,
                'fone_numero' => $whatsapp,
            ]);
            $mustSave = true;
        }

        // O usuário é salvo aqui apenas se um novo perfil é criado ou atualizações são feitas
        if ($mustSave) {
            $usuario->save();
        }

        return $usuario;
    }


    private function handleHomenagem(array $dadosHomenagem)
    {
        $homenagem = new Homenagem();
        $homenagem->preencher($dadosHomenagem);

        if ($homenagem->save()) {
            return $homenagem;
        }

        return null;
    }

    private function checkUploadDirectoryPermissions(): bool
    {
        $directory = storage_path('app/public/users-upload');
        Log::info('directory => ', (array)$directory);
        // Verificar se o diretório existe
        if (!is_dir($directory)) {
            Log::info('Entro no IF => !is_dir($directory)');
            return false; // O diretório não existe
        }

        // Verificar se o diretório tem permissões de escrita
        if (!is_writable($directory)) {
            Log::info('Entrou no IF => !is_writable($directory)');
            return false; // O diretório não tem permissão de escrita
        }

        return true;
    }
}
