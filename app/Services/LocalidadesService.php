<?php

namespace App\Services;

class LocalidadesService
{
    protected JsonFileLoader $jsonLoader;
    protected array $localidades;

    public function __construct(JsonFileLoader $jsonLoader)
    {
        $this->jsonLoader = $jsonLoader;
        $this->localidades = config('constants.localidades');
    }

    public function obterEstados(): array
    {
        $estados = $this->localidades;
        $ufs = [];
        foreach (array_keys($estados) as $sigla) {
            $ufs[] = [
                'sigla' => $sigla,
                'nome' => $estados[$sigla]['nome']
            ];
        }
        return $ufs;
    }

    public function obterSiglasDosEstados(): array
    {
        $siglas = array_keys($this->localidades);
        sort($siglas);
        return $siglas;
    }

    public function obterCidades(string $uf): array
    {
        $cidades = [];
        foreach ($this->obterNomeDasCidades($uf) as $nome) {
            $cidades[] = ['nome' => $nome];
        }
        return $cidades;
    }

    public function UFExiste(string $uf): bool
    {
        $ufUppercase = strtoupper($uf);
        return in_array($ufUppercase, $this->obterSiglasDosEstados());
    }

    public function obterNomeDasCidades(string $uf): array
    {
        $ufUppercase = strtoupper($uf);
        $cidades = $this->localidades[$ufUppercase]['cidades'];
        $collator = new \Collator('pt_BR');
        $collator->sort($cidades);
        return $cidades;
    }
}
