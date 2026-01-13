<?php

namespace App\Models\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function (Model $model) {
            // Se estiver rodando no console (seeder, migration), permite definir tenant_id manualmente
            if (app()->runningInConsole() && $model->getAttribute('tenant_id')) {
                return;
            }

            // Em execução normal (HTTP), força o tenant atual
            // Isso garante "Nunca aceitar tenant_id vindo de request"
            if (app()->bound('currentTenant')) {
                 $model->setAttribute('tenant_id', app('currentTenant')->id);
            } else {
                 $model->setAttribute('tenant_id', currentTenant()->id);
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
