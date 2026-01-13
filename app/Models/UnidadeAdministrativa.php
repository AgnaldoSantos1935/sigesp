<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeAdministrativa extends Model
{
    protected $table = 'unidades_administrativas';

    protected $fillable = [
        'regional_id',
        'endereco_id',
        'contato_id',
        'chefia_id',
        'nome',
        'tipo',
        'atividade',
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class);
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function contato()
    {
        return $this->belongsTo(Contato::class);
    }

    // Assuming ChefiaAdm exists or similar
    // public function chefia()
    // {
    //     return $this->belongsTo(ChefiaAdm::class, 'chefia_id');
    // }
}
