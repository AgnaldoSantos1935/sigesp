<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Dre;
use App\Models\Unidade;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OnboardingService
{
    /**
     * Create a new tenant and its initial admin user.
     *
     * @param string $name Name of the tenant
     * @param string $adminName Name of the admin user
     * @param string $adminEmail Email of the admin user
     * @param string $adminPassword Password for the admin user
     * @param string|null $cnpj CNPJ of the tenant (optional)
     * @return array Result with tenant and user
     */
    public function createTenant(string $name, string $adminName, string $adminEmail, string $adminPassword, ?string $cnpj = null): array
    {
        return DB::transaction(function () use ($name, $adminName, $adminEmail, $adminPassword, $cnpj) {
            // 0. Find Default Plan (Basic)
            $defaultPlan = Plan::where('slug', 'basic')->first();

            if (!$defaultPlan) {
                throw new \Exception("Default plan 'basic' not found. Please run 'php artisan db:seed --class=PlanSeeder'.");
            }

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $name,
                'cnpj' => $cnpj,
                'is_active' => true,
                'plan_id' => $defaultPlan ? $defaultPlan->id : null,
                'trial_ends_at' => now()->addDays(14), // 14 days trial
            ]);

            Log::info("Tenant created: {$tenant->name} (ID: {$tenant->id}) with Plan: " . ($defaultPlan->name ?? 'None'));

            // 2. Create Admin User
            // Note: tenant_id is set manually because we might not be in the tenant scope context yet
            $user = User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'tenant_id' => $tenant->id,
                'is_super_admin' => false,
            ]);

            Log::info("Admin created for tenant {$tenant->id}: {$user->email}");

            // 3. Run Initial Setup (Seeders/Basic Structure)
            $this->runInitialSetup($tenant);

            return [
                'tenant' => $tenant,
                'user' => $user,
            ];
        });
    }

    /**
     * Run initial setup for the new tenant.
     *
     * @param Tenant $tenant
     */
    protected function runInitialSetup(Tenant $tenant): void
    {
        // Set context for BelongsToTenant trait if needed (though we manually set IDs usually in setup)
        // But for Models that use the trait, we might need to fake the session or set the attribute explicitly.

        // Temporarily switch context to this tenant for safety
        $previousTenantId = session('tenant_id');
        session(['tenant_id' => $tenant->id]);

        try {
            // 3.1 Create Default DRE (Diretoria Regional de Ensino - Example Structure)
            // Even if the business logic isn't Education, we need a root structure.
            $dre = Dre::create([
                'nome' => 'Sede Administrativa - ' . $tenant->name,
                'codigo' => 'SEDE-01',
                'tenant_id' => $tenant->id, // Explicitly set just in case
            ]);

            // 3.2 Create Default Unidade (Unit)
            Unidade::create([
                'dre_id' => $dre->id,
                'nome' => 'Matriz',
                'codigo' => 'MATRIZ',
                'tipo' => 'ADMINISTRATIVA',
                'tenant_id' => $tenant->id,
            ]);

            Log::info("Initial structure created for tenant {$tenant->id}");

        } catch (\Exception $e) {
            Log::error("Error in initial setup for tenant {$tenant->id}: " . $e->getMessage());
            throw $e;
        } finally {
            // Restore previous context
            if ($previousTenantId) {
                session(['tenant_id' => $previousTenantId]);
            } else {
                session()->forget('tenant_id');
            }
        }
    }
}
