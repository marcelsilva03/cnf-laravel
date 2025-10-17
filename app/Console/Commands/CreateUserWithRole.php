<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserWithRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-with-role {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crie um novo usuário com  perfil, nome, e-mail e uma senha predifinida.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $givenRole = $this->argument('role');
        $email = "$givenRole@email.com";

        if (User::where('email', $email)->exists()) {
            $this->error("Já existe um usuário com este e-mail: {$email}.");
            return Command::FAILURE;
        }

        $role = UserRole::where('name', $givenRole)->first();
        if (!$role) {
            $availableRoles = UserRole::pluck('name')->toArray();
            $errorMessage = "Perfil {$givenRole} não existe. As opções disponíveis são: " . implode(', ', $availableRoles);

            $this->error($errorMessage);
            return Command::FAILURE;
        }

        $user = new User();
        $user->name = 'Nome do ' . ucfirst($givenRole);
        $user->email = $email;
        $user->password = '123';
        $user->role_id = $role->id;
        if (!$user->save()) {
            $this->error("Falha ao criar o usuário. Por favor, tente novamente mais tarde.");
            return Command::FAILURE;
        }

        $this->info("Usuário criado com sucesso! Nome: {$user->name}, E-mail: {$user->email}, Password: 123");

        return Command::SUCCESS;
    }
}
