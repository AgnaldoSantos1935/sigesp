<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Allow SuperAdmin to bypass tenant scope if explicitly requested via session
        if (session('ignore_tenant_scope') && auth()->check() && auth()->user()->is_super_admin) {
            return;
        }

        $builder->where($model->getTable() . '.tenant_id', currentTenant()->id);
    }
}
