<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) return;

        $admin = User::where('email', 'admin@demo.com')->first();
        $adminId = $admin ? $admin->id : null;

        // 1. Portaria de Designação (VALID)
        $typePortaria = DocumentType::where('slug', 'portaria')->first();
        if ($typePortaria) {
            Document::create([
                'tenant_id' => $tenant->id,
                'document_type_id' => $typePortaria->id,
                'numero' => 'PORT-2023/001',
                'data_emissao' => now()->subMonths(6),
                'descricao_resumida' => 'Portaria de designação de fiscal do contrato 001/2023',
                'conteudo_texto' => 'Fica designado o servidor Fulano de Tal como fiscal do contrato...',
                'status' => Document::STATUS_VALID,
                'created_by' => $adminId,
                'approved_by' => $adminId,
                'approved_at' => now()->subMonths(6),
            ]);
        }

        // 2. Contrato de Internet (VALID)
        $typeContrato = DocumentType::where('slug', 'contrato')->first();
        if ($typeContrato) {
            Document::create([
                'tenant_id' => $tenant->id,
                'document_type_id' => $typeContrato->id,
                'numero' => 'CONT-001/2023',
                'data_emissao' => now()->subYear(1),
                'descricao_resumida' => 'Contrato de provimento de internet fibra óptica 1GB',
                'conteudo_texto' => 'Contrato celebrado entre Instituição Demo e Provider X...',
                'status' => Document::STATUS_VALID,
                'created_by' => $adminId,
                'approved_by' => $adminId,
                'approved_at' => now()->subYear(1),
            ]);
        }

        // 3. Atesto Técnico (DRAFT)
        $typeAtesto = DocumentType::where('slug', 'atesto-conformidade')->first();
        if ($typeAtesto) {
            Document::create([
                'tenant_id' => $tenant->id,
                'document_type_id' => $typeAtesto->id,
                'numero' => 'ATESTO-TMP-001',
                'data_emissao' => now(),
                'descricao_resumida' => 'Atesto técnico de medição mensal - Janeiro/2024',
                'conteudo_texto' => 'Atesto para os devidos fins que os serviços foram prestados...',
                'status' => Document::STATUS_DRAFT,
                'created_by' => $adminId,
            ]);
        }

        // 4. Nota Fiscal (VALID)
        $typeNF = DocumentType::where('slug', 'nota-fiscal')->first();
        if ($typeNF) {
            Document::create([
                'tenant_id' => $tenant->id,
                'document_type_id' => $typeNF->id,
                'numero' => 'NF-12345',
                'data_emissao' => now()->subDays(5),
                'descricao_resumida' => 'Nota Fiscal referente aquisição de equipamentos de TI',
                'status' => Document::STATUS_VALID,
                'created_by' => $adminId,
                'approved_by' => $adminId,
                'approved_at' => now()->subDays(5),
            ]);
        }
    }
}
