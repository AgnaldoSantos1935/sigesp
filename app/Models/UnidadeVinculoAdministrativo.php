<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeVinculoAdministrativo extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'unidade_vinculos_administrativos';
    protected $guarded = ['id'];
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    public function dre(): BelongsTo
    {
        return $this->belongsTo(Dre::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
