<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'api_clients';

    protected $fillable = [
        'name',
        'api_key',
        'api_secret',
        'request_limit',
        'requests_made'
    ];

    protected $hidden = [
        'api_secret'
    ];
} 