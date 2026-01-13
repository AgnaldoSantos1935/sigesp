<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Designacao extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'designacoes';

    const PAPEL_GESTOR = 'GESTOR';
    const PAPEL_FISCAL_TECNICO = 'FISCAL_TECNICO';
    const PAPEL_FISCAL_ADMINISTRATIVO = 'FISCAL_ADMINISTRATIVO';

    protected $fillable = [
        'contrato_id',
        'user_id',
        'papel',
        'data_inicio',
        'data_fim',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
