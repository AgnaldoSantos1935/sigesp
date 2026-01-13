<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Modules
        $modules = [
            ['name' => 'Financeiro', 'slug' => 'financeiro', 'description' => 'Controle de Empenhos e Pagamentos'],
            ['name' => 'Contratos', 'slug' => 'contratos', 'description' => 'Gestão de Contratos e Vigências'],
            ['name' => 'Nota Técnica (NT)', 'slug' => 'nt', 'description' => 'Módulo de Notas Técnicas e Avarias'],
            ['name' => 'Fábrica de Software', 'slug' => 'fabrica_software', 'description' => 'Gestão de Demandas, OS e Medições'],
        ];

        $moduleMap = [];
        foreach ($modules as $mod) {
            $moduleMap[$mod['slug']] = Module::firstOrCreate(
                ['slug' => $mod['slug']],
                $mod
            );
        }

        // 2. Create Plans
        
        // BASIC: Apenas Contratos e Financeiro (Gestão básica)
        $basic = Plan::firstOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Basic',
                'description' => 'Gestão de Contratos e Financeiro Essencial',
                'price_monthly' => 199.00,
                'price_yearly' => 1990.00,
                'max_users' => 5,
                'max_storage_mb' => 1024, // 1GB
            ]
        );
        $basic->modules()->sync([
            $moduleMap['contratos']->id,
            $moduleMap['financeiro']->id,
        ]);

        // PRO: Inclui NT
        $pro = Plan::firstOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Pro',
                'description' => 'Gestão Completa + Notas Técnicas',
                'price_monthly' => 399.00,
                'price_yearly' => 3990.00,
                'max_users' => 20,
                'max_storage_mb' => 5120, // 5GB
            ]
        );
        $pro->modules()->sync([
            $moduleMap['contratos']->id,
            $moduleMap['financeiro']->id,
            $moduleMap['nt']->id,
        ]);

        // ENTERPRISE: Tudo liberado
        $enterprise = Plan::firstOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'description' => 'Solução Completa incluindo Fábrica de Software',
                'price_monthly' => 899.00,
                'price_yearly' => 8990.00,
                'max_users' => null, // Ilimitado
                'max_storage_mb' => 51200, // 50GB
            ]
        );
        $enterprise->modules()->sync([
            $moduleMap['contratos']->id,
            $moduleMap['financeiro']->id,
            $moduleMap['nt']->id,
            $moduleMap['fabrica_software']->id,
        ]);
    }
}
