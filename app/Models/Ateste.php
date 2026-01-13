<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ateste extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'data_ateste' => 'date',
    ];

    public function medicao(): BelongsTo
    {
        return $this->belongsTo(Medicao::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
