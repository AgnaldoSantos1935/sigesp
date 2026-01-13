<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empenho;
use App\Models\ContratoItem;
use App\Models\Document;
use App\Models\DocumentType;
use Carbon\Carbon;

class EmpenhoSeeder extends Seeder
{
    public function run(): void
    {
        // Set context
        session(['tenant_id' => 1]);

        $neType = DocumentType::firstOrCreate(['slug' => 'nota_empenho'], ['name' => 'Nota de Empenho']);

        $itens = ContratoItem::all();

        foreach ($itens as $index => $item) {
            // Create Document (NE)
            $doc = Document::create([
                'document_type_id' => $neType->id,
                'numero' => '2024NE' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'data_emissao' => Carbon::now()->subDays(rand(10, 60)),
                'descricao_resumida' => 'Empenho para ' . $item->descricao,
                'status' => 'VALID',
                'tenant_id' => 1,
            ]);

            // Empenho
            Empenho::create([
                'contrato_item_id' => $item->id,
                'numero' => '2024NE' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'ano' => 2024,
                'data_emissao' => $doc->data_emissao,
                'valor' => $item->valor_total * 0.8, // 80% do item empenhado
                'descricao' => 'Empenho ordinário referente ao item ' . $item->numero_item,
                'tipo' => $index % 2 == 0 ? 'ORDINARIO' : 'GLOBAL', // Mix types
                'status' => 'ATIVO',
                'document_id' => $doc->id,
                'tenant_id' => 1,
            ]);
        }
    }
}
