<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Tenant;
use App\Models\Vigencia;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VigenciaSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) {
            return;
        }

        $aditivoType = DocumentType::firstOrCreate(['slug' => 'termo_aditivo'], ['name' => 'Termo Aditivo']);

        // 1. Vigência Original para todos
        $contratos = Contrato::where('tenant_id', $tenant->id)->get();
        foreach ($contratos as $contrato) {
            // Find or create the contract document to link
            // In InstrumentoJuridicoSeeder we created documents for instruments, but Contracts might have their own "Signed Contract" document.
            // For simplicity, let's assume we link to the Instrument's document if no specific Contract document exists,
            // OR create a new "Contract Signed" document.
            // Let's create a specific document for the contract execution.

            $docContrato = Document::firstOrCreate(
                ['numero' => 'DOC-CT-' . $contrato->numero, 'tenant_id' => $tenant->id],
                [
                    'document_type_id' => 1, // Assuming 1 is some generic type or we fetch 'contrato' type
                    'data_emissao' => Carbon::createFromDate($contrato->ano, 1, 15),
                    'descricao_resumida' => 'Contrato Assinado ' . $contrato->numero,
                    'status' => Document::STATUS_VALID
                ]
            );

            if (!$contrato->vigencias()->where('tipo', Vigencia::TIPO_ORIGINAL)->exists()) {
                $contrato->vigencias()->create([
                    'data_inicio' => Carbon::createFromDate($contrato->ano, 1, 15), // Example date
                    'data_fim' => Carbon::createFromDate($contrato->ano + 1, 1, 14),
                    'tipo' => Vigencia::TIPO_ORIGINAL,
                    'document_id' => $docContrato->id,
                    'tenant_id' => $tenant->id
                ]);
            }
        }

        // 2. Aditivo de Prazo para Internet (005/2025)
        $ctNet = Contrato::where('numero', '005/2025')->where('tenant_id', $tenant->id)->first();
        if ($ctNet) {
            $docAditivo = Document::firstOrCreate(
                ['numero' => 'TA-01/2025', 'tenant_id' => $tenant->id],
                [
                    'document_type_id' => $aditivoType->id,
                    'data_emissao' => '2026-01-01',
                    'descricao_resumida' => '1º Termo Aditivo de Prazo',
                    'status' => Document::STATUS_VALID
                ]
            );

            // Check if exists to avoid overlap error or duplication
            if (!$ctNet->vigencias()->where('tipo', Vigencia::TIPO_ADITIVO)->exists()) {
                // Get last vigencia end
                $lastVigencia = $ctNet->vigencias()->orderBy('data_fim', 'desc')->first();
                $inicio = Carbon::parse($lastVigencia->data_fim)->addDay();
                $fim = $inicio->copy()->addYear()->subDay();

                $ctNet->vigencias()->create([
                    'data_inicio' => $inicio,
                    'data_fim' => $fim,
                    'tipo' => Vigencia::TIPO_ADITIVO,
                    'document_id' => $docAditivo->id,
                    'tenant_id' => $tenant->id
                ]);
            }
        }
    }
}
