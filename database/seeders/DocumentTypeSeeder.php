<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Portaria',
                'slug' => 'portaria',
                'description' => 'Ato administrativo de designação ou regulamentação',
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Contrato',
                'slug' => 'contrato',
                'description' => 'Instrumento jurídico contratual',
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Aditivo',
                'slug' => 'aditivo',
                'description' => 'Termo aditivo a contrato',
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Atesto de Conformidade',
                'slug' => 'atesto-conformidade',
                'description' => 'Atesto técnico de recebimento de bens ou serviços',
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Nota Fiscal',
                'slug' => 'nota-fiscal',
                'description' => 'Documento fiscal de cobrança',
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Relatório Técnico',
                'slug' => 'relatorio-tecnico',
                'description' => 'Relatório de vistoria ou execução',
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Nota de Empenho',
                'slug' => 'nota-empenho',
                'description' => 'Documento financeiro de empenho',
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Laudo Técnico / Evidência',
                'slug' => 'laudo-tecnico',
                'description' => 'Laudo ou evidência técnica',
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Anexo de Demanda',
                'slug' => 'anexo-demanda',
                'description' => 'Documento anexo a uma demanda',
                'requires_approval' => false,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}
