<?php

namespace Database\Seeders;

use App\Models\Dre;
use Illuminate\Database\Seeder;

class DreSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $dres = [
            ['nome' => 'DRE Centro-Sul', 'codigo' => 'DRE-CS', 'status' => 'ATIVO'],
            ['nome' => 'DRE Norte', 'codigo' => 'DRE-N', 'status' => 'ATIVO'],
            ['nome' => 'DRE Leste', 'codigo' => 'DRE-L', 'status' => 'ATIVO'],
            ['nome' => 'DRE Oeste', 'codigo' => 'DRE-O', 'status' => 'ATIVO'],
            ['nome' => 'DRE Rural', 'codigo' => 'DRE-R', 'status' => 'ATIVO'],
            ['nome' => 'Sede Administrativa', 'codigo' => 'SEDE', 'status' => 'ATIVO'],
        ];

        foreach ($dres as $dre) {
            Dre::firstOrCreate(
                ['codigo' => $dre['codigo']],
                array_merge($dre, ['tenant_id' => 1])
            );
        }
    }
}
