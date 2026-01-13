<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Models\Unidade;
use App\Policies\UnidadePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/tenant.php');

        // Bind 'currentTenant' to the container for dependency injection and testing
        $this->app->bind('currentTenant', function () {
            return currentTenant();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Policies
        Gate::policy(Unidade::class, UnidadePolicy::class);

        // Gate global para verificar acesso a módulos
        // Uso: Gate::authorize('access-module', 'financeiro');
        // Blade: @can('access-module', 'financeiro')
        Gate::define('access-module', function ($user, $moduleSlug) {
            // Se for SuperAdmin ignorar? Talvez, mas melhor testar a lógica do Tenant.
            // Mas se for SuperAdmin com Global View, pode não ter Tenant...
            if ($user->is_super_admin && session('ignore_tenant_scope')) {
                return true;
            }

            // Recupera o tenant atual
            $tenantId = currentTenant()->id;
            if (!$tenantId) return false;

            // Busca o tenant (idealmente cacheado)
            $tenant = Tenant::find($tenantId);
            if (!$tenant) return false;

            return $tenant->hasModule($moduleSlug);
        });

        // Gates específicos para o Menu (AdminLTE 'can' support)
        Gate::define('module-financeiro', fn($user) => Gate::allows('access-module', 'financeiro'));
        Gate::define('module-nt', fn($user) => Gate::allows('access-module', 'nt'));
        Gate::define('module-fabrica_software', fn($user) => Gate::allows('access-module', 'fabrica_software'));
    }
}
