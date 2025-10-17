<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class JsonFileLoader
{
    public function loadJson($filename)
    {
        $path = resource_path("json/{$filename}");
        if (!File::exists($path)) {
            return [];
        }
        $json = File::get($path);
        return json_decode($json, true);
    }
}
