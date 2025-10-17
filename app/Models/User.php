<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    const STATUS = [
        'INATIVO' => 0,
        'ATIVO' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'integer',
    ];

    /**
     * Implementação da interface FilamentUser
     * Define quais usuários podem acessar o painel Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Log para debug
        \Log::info('Checking panel access', [
            'user_id' => $this->id,
            'user_email' => $this->email,
            'user_status' => $this->status,
            'panel_id' => $panel->getId()
        ]);

        // Verificar se o usuário está ativo
        if ($this->status !== self::STATUS['ATIVO']) {
            \Log::warning('User access denied - inactive status', [
                'user_id' => $this->id,
                'status' => $this->status
            ]);
            return false;
        }

        $allowedRoles = [
            'admin', 
            'socio-gestor',
            'proprietario',  // CORREÇÃO: Adicionado role proprietario
            'clienteapi', 
            'solicitante', 
            'financeiro', 
            'moderador',
            'pesquisador'
        ];

        $userRoles = $this->roles->pluck('name')->toArray();
        $hasAccess = $this->hasAnyRole($allowedRoles);

        \Log::info('Panel access check result', [
            'user_id' => $this->id,
            'user_roles' => $userRoles,
            'allowed_roles' => $allowedRoles,
            'has_access' => $hasAccess
        ]);

        return $hasAccess;
    }

    public function registroPrevio(array $dados): string
    {
        // Verificar se email já existe
        if (User::where('email', $dados['email'])->exists()) {
            throw new \Exception('Email já está em uso.');
        }

        // Gerar senha mais segura (12 caracteres)
        $password = bin2hex(random_bytes(6));
        
        $this->name = $dados['nome'];
        $this->email = $dados['email'];
        $this->password = Hash::make($password);
        $this->status = self::STATUS['ATIVO'];
        
        // Salvar no banco
        $this->save();
        
        // Atribuir role após salvar
        $this->assignRole('solicitante');

        return $password;
    }

    /**
     * Relação com o perfil do usuário
     */
    public function perfil(): HasOne
    {
        return $this->hasOne(PerfilUsuario::class, 'user_id');
    }

    // CORREÇÃO: Removida relação órfã com UserRole (sistema antigo)
    // Agora usamos apenas o sistema Spatie: $user->roles ou $user->hasRole()

    public function apiClient(): HasOne
    {
        return $this->hasOne(APIClient::class, 'user_email', 'email');
    }

    public function faturamentos()
    {
        return $this->hasMany(Faturamento::class);
    }

    public function can($ability, $arguments = []): bool
    {
        if ($this->hasRole(['admin', 'socio-gestor'])) {
            return true;
        }

        return parent::can($ability, $arguments);
    }

    public static function statusList(): array
    {
        return array_flip(self::STATUS);
    }
}
