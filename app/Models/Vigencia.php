<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vigencia extends Model
{
    use BelongsToTenant, SoftDeletes;

    const TIPO_ORIGINAL = 'ORIGINAL';
    const TIPO_ADITIVO = 'ADITIVO';

    protected $fillable = [
        'contrato_id',
        'data_inicio',
        'data_fim',
        'tipo',
        'document_id',
        'tenant_id'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
