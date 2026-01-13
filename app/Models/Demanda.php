<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demanda extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $guarded = ['id'];

    public function demandante(): MorphTo
    {
        return $this->morphTo();
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
