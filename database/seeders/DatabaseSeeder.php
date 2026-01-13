<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Agnaldo Santos',
            'email' => 'agnaldosantos1935@gmail.com',
            'password' => bcrypt('S@n#t0s.100'),
        ]);

        $this->call([
            TenantSeeder::class, // Criar Tenant Demo antes de tudo
            UserSeeder::class,   // Criar Admin do Tenant
            PlanSeeder::class, // Planos SaaS devem ser criados primeiro
            DocumentTypeSeeder::class, // Tipos de Documentos (Global)
            DreSeeder::class,
            UnidadeSeeder::class,
            UnidadeVinculoAdministrativoSeeder::class,
            InstrumentoJuridicoSeeder::class,
            ContratoSeeder::class,
            ContratoItemSeeder::class,
            VigenciaSeeder::class,
            DesignacaoSeeder::class,
            EmpenhoSeeder::class,
            PagamentoSeeder::class,
            AvariaSeeder::class,
            RecebimentoSeeder::class,
            NecessidadeSeeder::class,
            DocumentSeeder::class, // Documentos
            DocumentFileSeeder::class, // Arquivos dos Documentos
            DocumentLinkSeeder::class, // Vínculos dos Documentos
        ]);
    }
}
