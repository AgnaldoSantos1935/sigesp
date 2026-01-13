<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apostilamento extends Model
{
    protected $table = 'apostilamentos';

    protected $fillable = [
        'instrumento_juridico_id',
        'numero',
        'processo',
        'objeto',
        'data_publicacao',
        'data_assinatura',
    ];

    public function instrumento()
    {
        return $this->belongsTo(InstrumentoJuridico::class, 'instrumento_juridico_id');
    }
}

