<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PessoaFisica extends Model
{
    use SoftDeletes;

    protected $table = 'pessoas_fisicas'; // 👈 AJUSTE PARA O NOME REAL
    protected $fillable = [
        'user_id','nome_completo','cpf','rg','data_nascimento','genero','ativo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'pessoa_enderecos')
            ->withPivot(['tipo','principal'])
            ->withTimestamps();
    }

    public function designacoes()
    {
        return $this->hasMany(Designacao::class);
    }
}

