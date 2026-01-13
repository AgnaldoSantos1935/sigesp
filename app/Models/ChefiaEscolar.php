<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChefiaEscolar extends Model
{
    protected $table = 'chefias_escolares';

    protected $fillable = [
        'pessoa_fisica_id',
        'data_inicio',
        'data_final',
        'titulo',
    ];

    public function pessoaFisica()
    {
        return $this->belongsTo(PessoaFisica::class, 'pessoa_fisica_id');
    }
}
