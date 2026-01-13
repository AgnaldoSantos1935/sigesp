<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Dre;
use App\Models\Unidade;
use App\Models\UnidadeVinculoAdministrativo;
use Illuminate\Database\Seeder;

class UnidadeVinculoAdministrativoSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        // Ensure Document Type
        $portariaType = DocumentType::firstOrCreate(
            ['slug' => 'portaria'],
            ['name' => 'Portaria', 'tenant_id' => 1]
        );

        // Get Units
        $escolaPedroII = Unidade::where('nome', 'Escola Estadual Pedro II')->first();
        $dreCS = Dre::where('codigo', 'DRE-CS')->first();

        if (!$escolaPedroII || !$dreCS) {
            return;
        }

        // 1. Vínculo Atual (recente)
        $docPortaria = Document::firstOrCreate(
            ['numero' => 'PORT-DES-2023/001'],
            [
                'document_type_id' => $portariaType->id,
                'data_emissao' => now()->subMonths(6),
                'status' => 'VALID',
                'descricao_resumida' => 'Designação Gestor Pedro II 2023',
                'tenant_id' => 1
            ]
        );

        UnidadeVinculoAdministrativo::firstOrCreate(
            [
                'unidade_id' => $escolaPedroII->id,
                'dre_id' => $dreCS->id,
                'data_inicio' => now()->subMonths(6)->format('Y-m-d'),
            ],
            [
                'dirigente_nome' => 'Maria Atual',
                'dirigente_cargo' => 'Diretor Escolar',
                'document_id' => $docPortaria->id,
                'data_fim' => null, // Vigente
                'tenant_id' => 1,
                'created_by' => 1 // Admin user
            ]
        );

        // 2. Vínculo Histórico (Antigo)
        $docPortariaAntiga = Document::firstOrCreate(
            ['numero' => 'PORT-DES-2020/050'],
            [
                'document_type_id' => $portariaType->id,
                'data_emissao' => now()->subYears(3),
                'status' => 'VALID',
                'descricao_resumida' => 'Designação Gestor Pedro II 2020',
                'tenant_id' => 1
            ]
        );

        UnidadeVinculoAdministrativo::firstOrCreate(
            [
                'unidade_id' => $escolaPedroII->id,
                'dre_id' => $dreCS->id,
                'data_inicio' => now()->subYears(3)->format('Y-m-d'),
            ],
            [
                'dirigente_nome' => 'João Antigo',
                'dirigente_cargo' => 'Diretor Escolar',
                'document_id' => $docPortariaAntiga->id,
                'data_fim' => now()->subMonths(6)->format('Y-m-d'), // Encerrou quando a nova começou
                'tenant_id' => 1,
                'created_by' => 1
            ]
        );
    }
}
