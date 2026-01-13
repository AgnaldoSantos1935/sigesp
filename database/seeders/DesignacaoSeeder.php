<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\Designacao;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DesignacaoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) {
            return;
        }

        $portariaType = DocumentType::firstOrCreate(['slug' => 'portaria'], ['name' => 'Portaria']);

        // Create specific users for roles
        $fiscal = User::firstOrCreate(
            ['email' => 'fiscal1@demo.com'],
            [
                'name' => 'Fiscal Técnico Padrão',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id
            ]
        );

        $gestor = User::firstOrCreate(
            ['email' => 'gestor1@demo.com'],
            [
                'name' => 'Gestor de Contratos Padrão',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id
            ]
        );

        // Substitute Fiscal (for history)
        $fiscalSub = User::firstOrCreate(
            ['email' => 'fiscal2@demo.com'],
            [
                'name' => 'Fiscal Técnico Substituto',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id
            ]
        );

        $contratos = Contrato::where('tenant_id', $tenant->id)->get();

        foreach ($contratos as $contrato) {
            // Documento de Designação Inicial
            $docPortaria = Document::firstOrCreate(
                ['numero' => 'PORT-001-' . $contrato->numero, 'tenant_id' => $tenant->id],
                [
                    'document_type_id' => $portariaType->id,
                    'data_emissao' => Carbon::createFromDate($contrato->ano, 1, 10),
                    'descricao_resumida' => 'Designação Inicial Gestor/Fiscal ' . $contrato->numero,
                    'status' => Document::STATUS_VALID
                ]
            );

            // 1. Gestor
            if (!$contrato->designacoes()->where('papel', Designacao::PAPEL_GESTOR)->exists()) {
                $contrato->designacoes()->create([
                    'user_id' => $gestor->id,
                    'papel' => Designacao::PAPEL_GESTOR,
                    'data_inicio' => Carbon::createFromDate($contrato->ano, 1, 15), // Same as vigencia start
                    'document_id' => $docPortaria->id,
                    'tenant_id' => $tenant->id
                ]);
            }

            // 2. Fiscal Técnico (Initial)
            // If Contract is the Dell one (001/2025), let's create a history
            if ($contrato->numero === '001/2025') {
                // Check if we already have history to be idempotent
                $hasFiscal = $contrato->designacoes()->where('papel', Designacao::PAPEL_FISCAL_TECNICO)->exists();

                if (!$hasFiscal) {
                    // Old Fiscal (Jan to June)
                    $contrato->designacoes()->create([
                        'user_id' => $fiscal->id,
                        'papel' => Designacao::PAPEL_FISCAL_TECNICO,
                        'data_inicio' => Carbon::createFromDate($contrato->ano, 1, 15),
                        'data_fim' => Carbon::createFromDate($contrato->ano, 6, 30),
                        'document_id' => $docPortaria->id,
                        'tenant_id' => $tenant->id
                    ]);

                    // New Fiscal (July onwards)
                    $docPortariaSub = Document::firstOrCreate(
                        ['numero' => 'PORT-SUB-' . $contrato->numero, 'tenant_id' => $tenant->id],
                        [
                            'document_type_id' => $portariaType->id,
                            'data_emissao' => Carbon::createFromDate($contrato->ano, 6, 25),
                            'descricao_resumida' => 'Substituição de Fiscal ' . $contrato->numero,
                            'status' => Document::STATUS_VALID
                        ]
                    );

                    $contrato->designacoes()->create([
                        'user_id' => $fiscalSub->id,
                        'papel' => Designacao::PAPEL_FISCAL_TECNICO,
                        'data_inicio' => Carbon::createFromDate($contrato->ano, 7, 1),
                        'document_id' => $docPortariaSub->id,
                        'tenant_id' => $tenant->id
                    ]);
                }
            } else {
                // Standard Fiscal for others
                if (!$contrato->designacoes()->where('papel', Designacao::PAPEL_FISCAL_TECNICO)->exists()) {
                    $contrato->designacoes()->create([
                        'user_id' => $fiscal->id,
                        'papel' => Designacao::PAPEL_FISCAL_TECNICO,
                        'data_inicio' => Carbon::createFromDate($contrato->ano, 1, 15),
                        'document_id' => $docPortaria->id,
                        'tenant_id' => $tenant->id
                    ]);
                }
            }
        }
    }
}
