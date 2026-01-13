<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstrumentoJuridico extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'instrumentos_juridicos';

    protected $fillable = [
        'tipo',
        'numero',
        'ano',
        'objeto',
        'document_id',
        'status',
        'tenant_id'
    ];

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
