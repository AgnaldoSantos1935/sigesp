<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    protected $table = 'contatos';

    protected $fillable = [
        'email_1',
        'email_2',
        'telefone_fixo',
        'celular_1',
        'celular_2',
        'rede_social_1',
        'rede_social_2',
        'pessoa_fisica_id',
    ];
}
