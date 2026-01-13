<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\Document;
use App\Models\DocumentLink;
use App\Models\Tenant;
use App\Models\Unidade;
use Illuminate\Database\Seeder;

class DocumentLinkSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) return;

        // Ensure we have some fake entities to link to if they don't exist
        // Since we are seeding, we might need to rely on existing ones or create dummies if not present
        // For this seeder, we will look for Documents created by DocumentSeeder

        $portaria = Document::where('tenant_id', $tenant->id)->where('numero', 'PORT-2023/001')->first();
        $contratoDoc = Document::where('tenant_id', $tenant->id)->where('numero', 'CONT-001/2023')->first();
        $atesto = Document::where('tenant_id', $tenant->id)->where('numero', 'ATESTO-TMP-001')->first();

        // Mocking/Finding Linked Entities
        // Ideally these should be real records.
        // Let's check if there are any Unidade or Contrato. If not, we skip or create dummy.

        // Try to find a Unidade or create a dummy one for linking purposes if possible
        // But Unidade requires more complex seeding. We'll try to fetch first.
        $unidade = Unidade::where('tenant_id', $tenant->id)->first();

        // Link Portaria to Unidade (if exists)
        if ($portaria && $unidade) {
            DocumentLink::create([
                'tenant_id' => $tenant->id,
                'document_id' => $portaria->id,
                'linked_type' => Unidade::class,
                'linked_id' => $unidade->id,
                'link_type' => 'DESIGNACAO', // Purpose
                'created_at' => $portaria->created_at,
            ]);
        }

        // Link Atesto to Contrato (Document) or Contrato Model
        // Since we created a Document for Contrato, let's link Atesto to that Document (Doc to Doc link is possible? Model says linked is MorphTo)
        // Usually Atesto links to Contrato Model.
        // Let's create a dummy Contrato Model entry to link to.

        $contratoModel = Contrato::where('tenant_id', $tenant->id)->where('numero', '001/2023')->first();
        if (!$contratoModel && $contratoDoc) {
             // Create dummy document for instrumento if needed
             $docInstrumento = Document::firstOrCreate(
                 ['numero' => 'DOC-INSTR-DUMMY-001', 'tenant_id' => $tenant->id],
                 [
                     'document_type_id' => 1, // Assuming 1 exists or fetch one
                     'descricao_resumida' => 'Doc Instrumento Dummy',
                     'data_emissao' => now()->subYear(1),
                     'status' => 'VALID',
                     'created_by' => 1
                 ]
             );

             // Ensure InstrumentoJuridico exists for the dummy contract
             $instrumento = \App\Models\InstrumentoJuridico::firstOrCreate(
                 ['numero' => 'INSTR-DUMMY-001', 'tenant_id' => $tenant->id],
                 [
                     'ano' => 2023,
                     'objeto' => 'Instrumento Jurídico Dummy para Contrato 001/2023',
                     'tipo' => 'CONTRATO',
                     'document_id' => $docInstrumento->id,
                     'status' => 'ATIVO'
                 ]
             );

            // Create a dummy contract model matching the document
             $contratoModel = Contrato::create([
                 'tenant_id' => $tenant->id,
                 'instrumento_juridico_id' => $instrumento->id, // Required field
                 'numero' => '001/2023',
                 'ano' => 2023,
                 'objeto' => 'Contrato Dummy de Aquisição',
                 'fornecedor_nome' => 'Provider X Ltda',
                 'fornecedor_cnpj' => '99.999.999/0001-99',
                 'status' => 'ATIVO',
                 'tipo_contrato' => 'AQUISICAO', // Campo obrigatório
             ]);
        }

        if ($atesto && $contratoModel) {
            DocumentLink::create([
                'tenant_id' => $tenant->id,
                'document_id' => $atesto->id,
                'linked_type' => Contrato::class,
                'linked_id' => $contratoModel->id,
                'link_type' => 'ATESTO',
                'created_at' => $atesto->created_at,
            ]);
        }

        // Link Contrato Document to Contrato Model
        if ($contratoDoc && $contratoModel) {
            DocumentLink::create([
                'tenant_id' => $tenant->id,
                'document_id' => $contratoDoc->id,
                'linked_type' => Contrato::class,
                'linked_id' => $contratoModel->id,
                'link_type' => 'EXECUCAO', // The document IS the execution instrument
                'created_at' => $contratoDoc->created_at,
            ]);
        }
    }
}
