<?php

if (! function_exists('currentTenant')) {
    /**
     * Get the current tenant.
     *
     * @return object
     */
    function currentTenant()
    {
        // 1. Try to get from session
        $tenantId = session('tenant_id');

        // 2. Fallback to auth user if available (future proofing)
        if (! $tenantId && auth()->check()) {
            $tenantId = auth()->user()->tenant_id ?? null;
        }

        // 3. Fallback to default (System)
        if (! $tenantId) {
            $tenantId = 1;
        }

        // Return an object that mimics a Tenant model
        return (object) ['id' => $tenantId];
    }
}
