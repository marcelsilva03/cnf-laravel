<?php

namespace App\Rules;

use App\Models\Plano;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlanoFaixaValidation implements ValidationRule
{
    protected string $campo;
    protected ?int $planoIdExcluir;

    public function __construct(string $campo, ?int $planoIdExcluir = null)
    {
        $this->campo = $campo;
        $this->planoIdExcluir = $planoIdExcluir;
    }

    /**
     * Executa a validação das regras de negócio para os planos
     * Implementa as 4 regras especificadas no Card #13
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Obter dados do formulário através do request
        $request = request();
        $faixaInicial = $request->input('faixa_inicial');
        $faixaFinal = $request->input('faixa_final');

        // Converter para inteiros se necessário
        $faixaInicial = is_numeric($faixaInicial) ? (int) $faixaInicial : null;
        $faixaFinal = is_numeric($faixaFinal) ? (int) $faixaFinal : null;

        // REGRA A: Verificar se faixa inicial > faixa final
        if ($this->campo === 'faixa_inicial' && $faixaInicial !== null && $faixaFinal !== null) {
            if ($faixaInicial > $faixaFinal) {
                $fail('A faixa inicial não pode ser maior que a faixa final.');
                return;
            }
        }

        // REGRA B: Verificar valores negativos
        if ($this->campo === 'faixa_inicial' && $faixaInicial !== null && $faixaInicial < 0) {
            $fail('A faixa inicial não pode ser negativa.');
            return;
        }

        if ($this->campo === 'faixa_final' && $faixaFinal !== null && $faixaFinal < 0) {
            $fail('A faixa final não pode ser negativa.');
            return;
        }

        // REGRAS C e D: Verificar duplicação e sobreposição apenas quando ambos os campos estão preenchidos
        if ($faixaInicial !== null) {
            $this->verificarConflitosComPlanosExistentes($faixaInicial, $faixaFinal, $fail);
        }
    }

    /**
     * Verifica conflitos com planos existentes (Regras C e D)
     */
    protected function verificarConflitosComPlanosExistentes(?int $faixaInicial, ?int $faixaFinal, Closure $fail): void
    {
        $query = Plano::query();

        // Excluir o plano atual durante edição
        if ($this->planoIdExcluir) {
            $query->where('id', '!=', $this->planoIdExcluir);
        }

        $planosExistentes = $query->get();

        foreach ($planosExistentes as $plano) {
            $planoInicial = (int) $plano->faixa_inicial;
            $planoFinal = $plano->faixa_final ? (int) $plano->faixa_final : null;

            // REGRA C: Verificar duplicação exata de valores
            if ($this->verificarDuplicacaoExata($faixaInicial, $faixaFinal, $planoInicial, $planoFinal)) {
                $fail($this->getMensagemDuplicacao($planoInicial, $planoFinal));
                return;
            }

            // REGRA D: Verificar sobreposição de faixas
            if ($this->verificarSobreposicao($faixaInicial, $faixaFinal, $planoInicial, $planoFinal)) {
                $fail($this->getMensagemSobreposicao($planoInicial, $planoFinal));
                return;
            }
        }
    }

    /**
     * Verifica duplicação exata de valores (Regra C)
     */
    protected function verificarDuplicacaoExata(?int $faixaInicial, ?int $faixaFinal, int $planoInicial, ?int $planoFinal): bool
    {
        // Verificar se faixa inicial já existe
        if ($faixaInicial === $planoInicial) {
            return true;
        }

        // Verificar se faixa final já existe (apenas se ambas não forem null)
        if ($faixaFinal !== null && $planoFinal !== null && $faixaFinal === $planoFinal) {
            return true;
        }

        return false;
    }

    /**
     * Verifica sobreposição de faixas (Regra D)
     */
    protected function verificarSobreposicao(?int $faixaInicial, ?int $faixaFinal, int $planoInicial, ?int $planoFinal): bool
    {
        // Se o plano existente não tem faixa final (vai até infinito), considera até um valor muito alto
        $planoFinalEfetivo = $planoFinal ?? PHP_INT_MAX;
        $faixaFinalEfetiva = $faixaFinal ?? PHP_INT_MAX;

        // Verificar se a faixa inicial está dentro de um plano existente
        if ($faixaInicial >= $planoInicial && $faixaInicial <= $planoFinalEfetivo) {
            return true;
        }

        // Verificar se a faixa final está dentro de um plano existente
        if ($faixaFinal !== null && $faixaFinalEfetiva >= $planoInicial && $faixaFinalEfetiva <= $planoFinalEfetivo) {
            return true;
        }

        // Verificar se o novo plano engloba um plano existente
        if ($faixaInicial <= $planoInicial && $faixaFinalEfetiva >= $planoFinalEfetivo) {
            return true;
        }

        return false;
    }

    /**
     * Gera mensagem de erro para duplicação
     */
    protected function getMensagemDuplicacao(int $planoInicial, ?int $planoFinal): string
    {
        $faixaTexto = $planoFinal !== null 
            ? "de {$planoInicial} até {$planoFinal}"
            : "a partir de {$planoInicial}";
            
        return "Já existe um plano cadastrado com a faixa {$faixaTexto}.";
    }

    /**
     * Gera mensagem de erro para sobreposição
     */
    protected function getMensagemSobreposicao(int $planoInicial, ?int $planoFinal): string
    {
        $faixaTexto = $planoFinal !== null 
            ? "de {$planoInicial} até {$planoFinal}"
            : "a partir de {$planoInicial}";
            
        return "A faixa informada conflita com o plano existente que abrange {$faixaTexto}.";
    }
} 