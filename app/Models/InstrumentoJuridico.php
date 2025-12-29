<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstrumentoJuridico extends Model
{
    protected $fillable = [
        'tipo','processo_administrativo','objeto','valor_global',
        'vigencia_inicio','vigencia_fim'
    ];

    public function designacoes()
    {
        return $this->hasMany(Designacao::class);
    }
}

