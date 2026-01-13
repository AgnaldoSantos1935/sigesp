<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContratoItem extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'contrato_itens';

    protected $fillable = [
        'contrato_id',
        'descricao',
        'unidade_medida',
        'quantidade_contratada',
        'valor_unitario',
        'valor_total',
        'controle_execucao',
        'tenant_id'
    ];

    protected $casts = [
        'quantidade_contratada' => 'decimal:4',
        'valor_unitario' => 'decimal:4',
        'valor_total' => 'decimal:2',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function empenhos()
    {
        return $this->hasMany(Empenho::class);
    }
}
