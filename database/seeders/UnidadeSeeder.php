<?php

namespace Database\Seeders;

use App\Models\Dre;
use App\Models\Unidade;
use Illuminate\Database\Seeder;

class UnidadeSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);
        
        $dreCS = Dre::where('codigo', 'DRE-CS')->first();
        $dreN = Dre::where('codigo', 'DRE-N')->first();
        $sede = Dre::where('codigo', 'SEDE')->first();

        $unidades = [
            // Escolas Centro-Sul
            [
                'nome' => 'Escola Estadual Pedro II',
                'codigo_inep' => '13000001',
                'tipo' => 'ESCOLA',
                'dre_id' => $dreCS?->id,
            ],
            [
                'nome' => 'Escola Estadual Princesa Isabel',
                'codigo_inep' => '13000002',
                'tipo' => 'ESCOLA',
                'dre_id' => $dreCS?->id,
            ],
            // Escolas Norte
            [
                'nome' => 'Escola Estadual Senador Cunha Melo',
                'codigo_inep' => '13000003',
                'tipo' => 'ESCOLA',
                'dre_id' => $dreN?->id,
            ],
            // Administrativas
            [
                'nome' => 'Departamento de Logística',
                'codigo_inep' => null,
                'tipo' => 'ADMINISTRATIVA',
                'dre_id' => $sede?->id, // Sede
            ],
            [
                'nome' => 'Gerência de Merenda Escolar',
                'codigo_inep' => null,
                'tipo' => 'ADMINISTRATIVA',
                'dre_id' => $sede?->id,
            ],
        ];

        foreach ($unidades as $data) {
            Unidade::firstOrCreate(
                ['nome' => $data['nome']],
                array_merge($data, ['tenant_id' => 1])
            );
        }
    }
}
