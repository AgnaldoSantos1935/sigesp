<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complexidade;
use App\Models\Contrato;

class ComplexidadeSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $contratos = Contrato::all();

        if ($contratos->isEmpty()) {
            // Should probably create one if missing, but let's assume existence from previous steps
            return;
        }

        foreach ($contratos as $contrato) {
            $complexidades = [
                ['nome' => 'Baixa Complexidade', 'fator' => 1.0, 'valor_unitario' => 100.00],
                ['nome' => 'Média Complexidade', 'fator' => 1.5, 'valor_unitario' => 100.00], // 150.00 effective
                ['nome' => 'Alta Complexidade', 'fator' => 2.0, 'valor_unitario' => 100.00], // 200.00 effective
                ['nome' => 'UST Simples', 'fator' => 1.0, 'valor_unitario' => 85.50],
                ['nome' => 'Ponto de Função', 'fator' => 1.0, 'valor_unitario' => 500.00],
            ];

            foreach ($complexidades as $data) {
                Complexidade::firstOrCreate(
                    [
                        'contrato_id' => $contrato->id,
                        'nome' => $data['nome'],
                        'tenant_id' => 1
                    ],
                    [
                        'fator' => $data['fator'],
                        'valor_unitario' => $data['valor_unitario']
                    ]
                );
            }
        }
    }
}
