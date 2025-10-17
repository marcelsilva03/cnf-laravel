<?php

namespace App\Console\Commands;

use App\Models\APIClient;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GerarClienteAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api.gerar-client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um cliente de API com uma chave';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $limit = $this->option('limit');

        $apiKey = Str::random(32);
        $apiSecret = Str::random(32);

        ApiClient::create([
            'name' => $name,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'request_limit' => $limit,
        ]);

        $this->info("Cliente criado com sucesso!");
        $this->info("API Key: $apiKey");
        $this->info("API Secret: $apiSecret");
    }
}
