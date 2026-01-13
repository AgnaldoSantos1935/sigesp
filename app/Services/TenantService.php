<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Tenant;
use Illuminate\Support\Facades\Session;

class TenantService
{
    /**
     * Switch the current session to a specific tenant.
     * Only SuperAdmin should be able to do this freely.
     *
     * @param int $tenantId
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function switchTenant(int $tenantId): void
    {
        // Validate if tenant exists
        $tenant = Tenant::findOrFail($tenantId);

        $previousTenant = session('tenant_id');

        // Audit the switch
        $this->logAction('SWITCH_TENANT', Tenant::class, $tenantId, [
            'from_tenant' => $previousTenant,
            'to_tenant' => $tenantId,
            'tenant_name' => $tenant->name
        ]);

        Session::put('tenant_id', $tenantId);
        Session::forget('ignore_tenant_scope');
    }

    public function enableGlobalView(): void
    {
        if (!auth()->user()->is_super_admin) {
            abort(403, 'Unauthorized');
        }

        $this->logAction('ENABLE_GLOBAL_VIEW');

        Session::put('ignore_tenant_scope', true);
        // We might want to keep tenant_id as is or clear it,
        // but ignore_tenant_scope flag in Scope handles the query.
        // Creating records might still need a tenant_id though!
        // So keeping tenant_id as fallback is good.
    }

    public function disableGlobalView(): void
    {
        $this->logAction('DISABLE_GLOBAL_VIEW');
        Session::forget('ignore_tenant_scope');
    }

    public function logAction(string $action, ?string $modelType = null, ?int $modelId = null, ?array $payload = null)
    {
        AuditLog::create([
            'tenant_id' => session('tenant_id') ?? 1,
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'new_values' => $payload, // Using new_values to store metadata for generic actions
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
