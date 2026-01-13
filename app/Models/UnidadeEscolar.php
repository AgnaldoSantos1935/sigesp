<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeEscolar extends Model
{
    protected $table = 'unidades_escolares';
    protected $primaryKey = 'id_escola';

    protected $fillable = [
        'regional_id',
        'endereco_id',
        'contato_id',
        'chefia_escolar_id',
        'nome_escola',
        'codigo_inep',
        'municipio',
        'uf',
        'endereco',
        'restricao_atendimento',
        'localizacao',
        'localidade_diferenciada',
        'categoria_administrativa',
        'telefone',
        'dependencia_administrativa',
        'categoria',
        'conveniada',
        'regulamentacao_conselho',
        'porte',
        'etapa_modalidade',
        'outras_ofertas',
        'latitude',
        'longitude',
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function contato()
    {
        return $this->belongsTo(Contato::class, 'contato_id');
    }

    public function chefia()
    {
        return $this->belongsTo(ChefiaEscolar::class, 'chefia_escolar_id');
    }
}
