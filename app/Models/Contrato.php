<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contrato extends Model
{
    use BelongsToTenant, SoftDeletes;

    const STATUS_RASCUNHO = 'RASCUNHO';
    const STATUS_ATIVO = 'ATIVO';
    const STATUS_ENCERRADO = 'ENCERRADO';

    const TIPO_AQUISICAO = 'AQUISICAO';
    const TIPO_SERVICO = 'SERVICO';
    const TIPO_INTERNET = 'INTERNET';
    const TIPO_FABRICA_SOFTWARE = 'FABRICA_SOFTWARE';

    protected $fillable = [
        'instrumento_juridico_id',
        'numero',
        'ano',
        'objeto',
        'fornecedor_nome',
        'fornecedor_cnpj',
        'tipo_contrato',
        'status',
        'tenant_id'
    ];

    public function instrumentoJuridico(): BelongsTo
    {
        return $this->belongsTo(InstrumentoJuridico::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ContratoItem::class);
    }

    public function vigencias(): HasMany
    {
        return $this->hasMany(Vigencia::class);
    }

    public function designacoes(): HasMany
    {
        return $this->hasMany(Designacao::class);
    }

    // Helper para vigência atual
    public function vigenciaAtual()
    {
        return $this->hasOne(Vigencia::class)
            ->where('data_inicio', '<=', now())
            ->where('data_fim', '>=', now())
            ->latest('data_inicio');
    }
}
