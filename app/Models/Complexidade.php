<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complexidade extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'fator' => 'decimal:2',
        'valor_unitario' => 'decimal:2',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }
}
