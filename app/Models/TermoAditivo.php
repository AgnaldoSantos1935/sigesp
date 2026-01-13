<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermoAditivo extends Model
{
    protected $table = 'termos_aditivos';

    protected $fillable = [
        'instrumento_juridico_id',
        'numero',
        'processo',
        'objeto',
        'valor_ajuste',
        'data_assinatura',
        'data_publicacao',
    ];

    public function instrumento()
    {
        return $this->belongsTo(InstrumentoJuridico::class, 'instrumento_juridico_id');
    }
}

