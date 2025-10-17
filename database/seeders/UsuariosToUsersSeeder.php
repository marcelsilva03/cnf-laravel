<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuariosToUsersSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'solicitante']);
        $usuarios = DB::table('usuario')->get();

        $processedEmails = [];
        foreach ($usuarios as $usuario) {
            if (!$usuario->usr_email || in_array($usuario->usr_email, $processedEmails)) {
                continue;
            }
            $processedEmails[] = $usuario->usr_email;
            $user = DB::table('users')->insertGetId([
                'name' => $usuario->usr_nome,
                'email' => $usuario->usr_email,
                'password' => Hash::make($usuario->usr_senha),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('perfil_usuarios')->insert([
                'user_id' => $user,
                'old_id' => $usuario->usr_id,
                'data_cadastro' => $usuario->usr_data_cadastro,
                'ativo' => $usuario->usr_ativo,
                'login' => $usuario->usr_login,
                'cod_altera_senha' => $usuario->usr_cod_altera_senha,
                'email' => $usuario->usr_email,
                'email_confirmado' => $usuario->usr_email_confirmado,
                'nome' => $usuario->usr_nome,
                'responsavel' => $usuario->usr_responsavel,
                'data_nascimento' => $usuario->usr_data_nascimento,
                'sexo' => $usuario->usr_sexo,
                'cpf' => $usuario->usr_cpf,
                'rg' => $usuario->usr_rg,
                'endereco' => $usuario->usr_endereco,
                'endereco_numero' => $usuario->usr_endereco_numero,
                'endereco_complemento' => $usuario->usr_endereco_complemento,
                'endereco_bairro' => $usuario->usr_endereco_bairro,
                'endereco_cep' => $usuario->usr_endereco_cep,
                'endereco_id_ecd' => $usuario->usr_endereco_id_ecd,
                'fone_numero' => $usuario->usr_fone_numero,
                'id_fun' => $usuario->usr_id_fun,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => \App\Models\User::class,
                'model_id' => $user,
            ]);
        }
    }
}
