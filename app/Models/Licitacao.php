<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licitacao extends Model
{
    protected $table = 'licitacoes';

    protected $fillable = [
        'numero_licitacao',
        'numero_processo',
        'numero_edital',
        'modalidade',
        'objeto',
        'criterios',
        'habilitacao',
        'fundamento_legal',
        'data_publicacao',
        'data_encerramento',
    ];
}

