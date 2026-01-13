<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();

        if (!$tenant) {
            $this->command->error('Tenant demo not found. Run TenantSeeder first.');
            return;
        }

        // Create Admin User
        $adminEmail = 'admin@demo.com';
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Administrador Demo',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
                'is_super_admin' => true, // Assuming local admin is super for the tenant scope or actual super admin
            ]);
        }
    }
}
