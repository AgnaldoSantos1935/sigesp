<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdemServico extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'ordens_servico';

    protected $guarded = ['id'];

    protected $casts = [
        'data_emissao' => 'date',
        'prazo_execucao' => 'date',
        'valor_estimado' => 'decimal:2',
    ];

    public function demanda(): BelongsTo
    {
        return $this->belongsTo(Demanda::class);
    }

    public function contratoItem(): BelongsTo
    {
        return $this->belongsTo(ContratoItem::class);
    }

    public function atividades(): HasMany
    {
        return $this->hasMany(Atividade::class);
    }

    public function medicoes(): HasMany
    {
        return $this->hasMany(Medicao::class);
    }
}
