<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abrangencia extends Model
{
    use HasFactory;
    protected $table = 'abrangencia';
    protected $fillable = ['abr_id', 'abr_desc', 'abr_status'];

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacoes::class);
    }

}