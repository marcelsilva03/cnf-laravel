<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSocioGestorRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicionar o papel 'socio-gestor' na tabela roles (Spatie)
        if (!DB::table('roles')->where('name', 'socio-gestor')->exists()) {
            DB::table('roles')->insert([
                'name' => 'socio-gestor',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Adicionar na tabela user_roles
        if (!DB::table('user_roles')->where('name', 'socio-gestor')->exists()) {
            DB::table('user_roles')->insert([
                'name' => 'socio-gestor',
                'label' => 'Sócio-Gestor',
            ]);
        }

        // Adicionar um usuário de teste com o perfil socio-gestor
        if (!DB::table('users')->where('email', 'socio-gestor@email.com')->exists()) {
            // Obter o ID do user_role primeiro
            $userRoleId = DB::table('user_roles')->where('name', 'socio-gestor')->value('id');
            
            // Criar usuário com role_id
            $userId = DB::table('users')->insertGetId([
                'name' => 'Sócio-Gestor',
                'email' => 'socio-gestor@email.com',
                'password' => bcrypt('123'),
                'role_id' => $userRoleId,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obter o ID do role socio-gestor
            $roleId = DB::table('roles')->where('name', 'socio-gestor')->value('id');

            // Atribuir role ao usuário na tabela model_has_roles
            if ($roleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remover o usuário teste de socio-gestor
        $userId = DB::table('users')->where('email', 'socio-gestor@email.com')->value('id');
        if ($userId) {
            DB::table('model_has_roles')->where('model_id', $userId)->delete();
            DB::table('users')->where('id', $userId)->delete();
        }

        // Remover o papel socio-gestor da tabela roles
        DB::table('roles')->where('name', 'socio-gestor')->delete();
        
        // Remover da tabela user_roles
        DB::table('user_roles')->where('name', 'socio-gestor')->delete();
    }
} 