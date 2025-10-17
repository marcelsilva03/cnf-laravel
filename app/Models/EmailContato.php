<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailContato extends Model
{
    use HasFactory;
    protected $table = 'emails';
    protected $fillable = ['nome', 'email', 'telefone', 'mensagem', 'assunto'];
}
