<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demanda;
use App\Models\Unidade;
use App\Models\User;

class DemandaSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $unidades = Unidade::take(5)->get();
        $users = User::take(5)->get();

        if ($unidades->isEmpty() && $users->isEmpty()) return;

        // Demandas de Unidades
        foreach ($unidades as $unidade) {
            Demanda::create([
                'tenant_id' => 1,
                'demandante_type' => Unidade::class,
                'demandante_id' => $unidade->id,
                'titulo' => 'Desenvolvimento de Módulo de Relatórios para ' . $unidade->nome,
                'descricao' => 'Necessidade de relatórios customizados para a unidade escolar.',
                'prioridade' => 'ALTA',
                'status' => 'RASCUNHO', // Will be converted later or kept as draft
            ]);
        }

        // Demandas de Usuários (Setores Administrativos)
        foreach ($users as $user) {
            Demanda::create([
                'tenant_id' => 1,
                'demandante_type' => User::class,
                'demandante_id' => $user->id,
                'titulo' => 'Ajuste no Painel Administrativo',
                'descricao' => 'Solicitação de melhoria na UX do painel.',
                'prioridade' => 'MEDIA',
                'status' => 'RASCUNHO',
            ]);
        }
    }
}
