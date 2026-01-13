<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $guarded = ['id'];

    public function dre(): BelongsTo
    {
        return $this->belongsTo(Dre::class);
    }

    public function vinculosAdministrativos(): HasMany
    {
        return $this->hasMany(UnidadeVinculoAdministrativo::class);
    }

    public function vinculoAtual()
    {
        return $this->hasOne(UnidadeVinculoAdministrativo::class)
            ->whereNull('data_fim')
            ->latest('data_inicio');
    }

    /**
     * @deprecated Use vinculoAtual() instead
     */
    public function gestorAtual()
    {
        return $this->vinculoAtual();
    }
}
