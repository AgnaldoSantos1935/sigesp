<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstrumentoJuridicoItem extends Model
{
    protected $table = 'instrumento_juridico_items';

    protected $fillable = [
        'instrumento_juridico_id',
        'numero_item',
        'descricao',
        'unidade_medida',
        'quantidade_total',
        'valor_unitario',
        'valor_total',
    ];

    public function instrumento()
    {
        return $this->belongsTo(InstrumentoJuridico::class, 'instrumento_juridico_id');
    }

    /**
     * Unidades Escolares vinculadas ao item.
     */
    public function unidadesEscolares()
    {
        return $this->morphedByMany(UnidadeEscolar::class, 'unidade', 'instrumento_item_unidades')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    /**
     * Unidades Administrativas vinculadas ao item.
     */
    public function unidadesAdministrativas()
    {
        return $this->morphedByMany(UnidadeAdministrativa::class, 'unidade', 'instrumento_item_unidades')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}
