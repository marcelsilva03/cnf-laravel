<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faturamento;
use App\Models\User;

class FaturamentoSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        if (!empty($userIds)) {
            Faturamento::factory()->count(5)->create(['user_id' => $userIds[array_rand($userIds)]]);
        }
    }
}
