<?php

namespace App\Services;

class AbrangenciaService
{
    protected JsonFileLoader $jsonLoader;
    protected array $abrangencia;

    public function __construct(JsonFileLoader $jsonLoader)
    {
        $this->jsonLoader = $jsonLoader;
        $this->abrangencia = config('constants.abrangencia');
    }



}
