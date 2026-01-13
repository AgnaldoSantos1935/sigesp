<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PessoaJuridica extends Model
{
    protected $table = 'pessoas_juridicas';

    protected $fillable = [
        'endereco_id',
        'contato_id',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'cod_cnae',
        'ramo_atividade',
        'cod_natjuridica',
        'tipo_pessoa',
        'ativo',
    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function contato()
    {
        return $this->belongsTo(Contato::class);
    }

    public function representantes()
    {
        return $this->belongsToMany(PessoaFisica::class, 'pessoa_juridica_representantes')
                    ->withPivot(['tipo', 'inicio_vigencia', 'fim_vigencia'])
                    ->withTimestamps();
    }
}
