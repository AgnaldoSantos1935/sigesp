<?php

namespace App\Console\Commands;

use App\Services\OnboardingService;
use Illuminate\Console\Command;

class MakeTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tenant 
                            {name : The name of the tenant/company} 
                            {email : The email of the admin user} 
                            {--password= : The password for the admin (generated if empty)}
                            {--cnpj= : The CNPJ of the tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant and onboard it with initial data';

    /**
     * Execute the console command.
     */
    public function handle(OnboardingService $service)
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $cnpj = $this->option('cnpj');
        $password = $this->option('password') ?? 'password'; // Default for dev

        $this->info("Creating tenant: $name");
        $this->info("Admin Email: $email");

        try {
            $result = $service->createTenant($name, 'Admin ' . $name, $email, $password, $cnpj);
            
            $tenant = $result['tenant'];
            $user = $result['user'];

            $this->newLine();
            $this->info("✅ Tenant created successfully!");
            $this->table(
                ['ID', 'Name', 'CNPJ', 'Active'],
                [[$tenant->id, $tenant->name, $tenant->cnpj, $tenant->is_active ? 'Yes' : 'No']]
            );

            $this->info("✅ Admin user created!");
            $this->table(
                ['ID', 'Name', 'Email', 'Password'],
                [[$user->id, $user->name, $user->email, $password]]
            );

            $this->info("To login, switch context to Tenant ID: {$tenant->id}");

        } catch (\Exception $e) {
            $this->error("Failed to create tenant: " . $e->getMessage());
        }
    }
}
