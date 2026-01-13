<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dre extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $guarded = ['id'];

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class);
    }
}
