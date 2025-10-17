<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obito extends Model
{
    protected $fillable = [
        'nomesol', 'telsol', 'emailsol', 'comunicarobito', 'obito_nomeinst', 'obito_cpfinst',
        'obito_reginst', 'obito_eleitor', 'obito_nomepai', 'obito_nomemae', 'obito_nascf',
        'obito_dfalec', 'obito_estadoinstnome', 'obito_cidadeinstnome', 'obito_localfal',
        'obito_ecivil', 'comentarios',
    ];
}

