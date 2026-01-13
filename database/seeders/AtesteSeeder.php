<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicao;
use App\Models\User;
use App\Services\AtesteService;

class AtesteSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $medicoes = Medicao::where('status', 'RASCUNHO')->get();
        $user = User::first();

        if ($medicoes->isEmpty() || !$user) return;

        $atesteService = app(AtesteService::class);

        foreach ($medicoes as $medicao) {
            try {
                // 80% approval rate
                if (rand(1, 10) <= 8) {
                    $atesteService->atestar($medicao, $user, 'APROVADO', 'Serviços executados conforme especificação técnica.');
                } else {
                    $atesteService->atestar($medicao, $user, 'APROVADO_RESSALVAS', 'Aprovado, mas requer ajustes na documentação final.');
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
