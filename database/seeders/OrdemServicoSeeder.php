<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrdemServico;
use App\Models\Demanda;
use App\Models\ContratoItem;
use App\Models\Complexidade;
use App\Services\DemandaService;
use App\Services\OrdemServicoService;

class OrdemServicoSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $demandas = Demanda::where('status', 'RASCUNHO')->get();
        $contratoItem = ContratoItem::first(); // Assuming Fábrica de Software item
        
        if ($demandas->isEmpty() || !$contratoItem) return;

        $demandaService = app(DemandaService::class);
        $osService = app(OrdemServicoService::class);

        foreach ($demandas as $index => $demanda) {
            // Convert to OS
            $osData = [
                'tenant_id' => 1,
                'codigo' => 'OS-' . date('Y') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'data_emissao' => now(),
                'prazo_execucao' => now()->addDays(30),
                'status' => 'EM_EXECUCAO',
                'valor_estimado' => 0, // Will be updated by activities? Or logic handles it.
            ];

            try {
                $os = $demandaService->convertToOs($demanda, $contratoItem->id, $osData);

                // Add Activities
                // Get complexities for this contract
                $complexidades = Complexidade::where('contrato_id', $contratoItem->contrato_id)->get();
                
                if ($complexidades->isNotEmpty()) {
                    $comp = $complexidades->random();
                    
                    $atividadeData = [
                        'tenant_id' => 1,
                        'complexidade_id' => $comp->id,
                        'titulo' => 'Desenvolvimento Backend',
                        'descricao' => 'Implementação de APIs',
                        'quantidade' => rand(10, 100),
                    ];

                    $osService->addAtividade($os, $atividadeData);

                    // Add another activity
                    $comp2 = $complexidades->random();
                    $osService->addAtividade($os, [
                        'tenant_id' => 1,
                        'complexidade_id' => $comp2->id,
                        'titulo' => 'Frontend e Integração',
                        'descricao' => 'Telas e consumo de dados',
                        'quantidade' => rand(5, 50),
                    ]);
                }

            } catch (\Exception $e) {
                // Log or ignore if already converted
                continue;
            }
        }
    }
}
