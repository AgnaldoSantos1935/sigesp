<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\InstrumentoJuridico;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class InstrumentoJuridicoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) {
            return;
        }

        // Ensure Document Types exist
        $typeEdital = DocumentType::firstOrCreate(['slug' => 'edital'], ['name' => 'Edital', 'is_active' => true]);
        $typeAta = DocumentType::firstOrCreate(['slug' => 'ata_registro_preco'], ['name' => 'Ata de Registro de Preços', 'is_active' => true]);
        $typeDispensa = DocumentType::firstOrCreate(['slug' => 'dispensa'], ['name' => 'Dispensa de Licitação', 'is_active' => true]);

        // 1. Edital (para Internet)
        $docEdital = Document::firstOrCreate(
            ['numero' => 'EDITAL-005/2025', 'tenant_id' => $tenant->id],
            [
                'document_type_id' => $typeEdital->id,
                'data_emissao' => '2025-01-05',
                'descricao_resumida' => 'Edital para Contratação de Link de Internet',
                'status' => Document::STATUS_VALID,
                'created_by' => 1 // Assuming admin exists
            ]
        );

        InstrumentoJuridico::firstOrCreate(
            ['numero' => 'EDITAL-005/2025', 'tenant_id' => $tenant->id],
            [
                'ano' => 2025,
                'objeto' => 'Contratação de Serviço de Internet Dedicada',
                'tipo' => 'EDITAL',
                'document_id' => $docEdital->id,
                'status' => 'ATIVO'
            ]
        );

        // 2. Ata de Registro de Preços (para Dell e Simpress)
        $docAta = Document::firstOrCreate(
            ['numero' => 'ARP-001/2025', 'tenant_id' => $tenant->id],
            [
                'document_type_id' => $typeAta->id,
                'data_emissao' => '2025-01-10',
                'descricao_resumida' => 'ARP para Equipamentos de Informática',
                'status' => Document::STATUS_VALID,
                'created_by' => 1
            ]
        );

        InstrumentoJuridico::firstOrCreate(
            ['numero' => 'ARP-001/2025', 'tenant_id' => $tenant->id],
            [
                'ano' => 2025,
                'objeto' => 'Registro de Preços para Aquisição de Bens de Informática',
                'tipo' => 'ARP',
                'document_id' => $docAta->id,
                'status' => 'ATIVO'
            ]
        );

        // 3. Dispensa (para Fábrica de Software - Exemplo didático)
        $docDispensa = Document::firstOrCreate(
            ['numero' => 'DISP-020/2025', 'tenant_id' => $tenant->id],
            [
                'document_type_id' => $typeDispensa->id,
                'data_emissao' => '2025-01-15',
                'descricao_resumida' => 'Dispensa para Contratação Emergencial de Fábrica',
                'status' => Document::STATUS_VALID,
                'created_by' => 1
            ]
        );

        InstrumentoJuridico::firstOrCreate(
            ['numero' => 'DISP-020/2025', 'tenant_id' => $tenant->id],
            [
                'ano' => 2025,
                'objeto' => 'Contratação Emergencial de Fábrica de Software',
                'tipo' => 'DISPENSA',
                'document_id' => $docDispensa->id,
                'status' => 'ATIVO'
            ]
        );
    }
}
