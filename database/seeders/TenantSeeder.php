<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Check if demo tenant exists
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();

        if (!$tenant) {
            Tenant::create([
                'name' => 'Instituição Demo',
                'cnpj' => '12345678000199',
                'slug' => 'demo-instituicao',
                'is_active' => true,
                'trial_ends_at' => now()->addYear(),
            ]);
        }
    }
}
