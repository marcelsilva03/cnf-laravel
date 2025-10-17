<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCredencial extends Model
{
    use HasFactory;

    protected $table = 'api_credenciais';

    protected $fillable = [
        'user_id',
        'api_client_token',
        'api_client_key',
        'ativo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateCredentials($userId)
    {
        $token = \Illuminate\Support\Str::random(60);
        $key = \Illuminate\Support\Str::random(60);
        return [
            'api_client_token' => $token,
            'api_client_key' => bcrypt($key),
        ];
    }
}
