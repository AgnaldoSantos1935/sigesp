<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@sigesp.com'],
            [
                'name' => 'Super Admin Global',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'is_super_admin' => true
            ]
        );

        // 2. Create User in Tenant 2
        $userTenant2 = User::firstOrCreate(
            ['email' => 'user@tenant2.com'],
            [
                'name' => 'User Tenant 2',
                'password' => Hash::make('password'),
                'tenant_id' => 2,
                'is_super_admin' => false
            ]
        );
    }
}
