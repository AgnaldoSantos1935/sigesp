<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicao extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'medicoes';

    protected $guarded = ['id'];

    protected $casts = [
        'data_medicao' => 'date',
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
        'valor_medido' => 'decimal:2',
    ];

    public function ordemServico(): BelongsTo
    {
        return $this->belongsTo(OrdemServico::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function ateste(): HasOne
    {
        return $this->hasOne(Ateste::class);
    }
}
