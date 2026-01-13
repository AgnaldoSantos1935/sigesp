<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recebimento;
use App\Models\ContratoItem;
use App\Models\Unidade;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentType;
use Carbon\Carbon;

class RecebimentoSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $guiaType = DocumentType::firstOrCreate(['slug' => 'guia_remessa'], ['name' => 'Guia de Remessa']);

        $itens = ContratoItem::all();
        // Distribute to different units if possible, or just first
        $unidades = Unidade::all();
        $user = User::first();

        if ($itens->isEmpty() || $unidades->isEmpty()) return;

        foreach ($itens as $index => $item) {
            $unidade = $unidades->random();

            // Documento
            $doc = Document::create([
                'document_type_id' => $guiaType->id,
                'numero' => 'GUIA-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'data_emissao' => Carbon::now()->subDays(rand(1, 10)),
                'descricao_resumida' => 'Entrega de item contratual: ' . $item->descricao,
                'status' => 'VALID',
                'tenant_id' => 1,
            ]);

            Recebimento::create([
                'unidade_id' => $unidade->id,
                'contrato_item_id' => $item->id,
                'descricao_item' => $item->descricao,
                'quantidade' => rand(5, 50),
                'data_recebimento' => Carbon::now(),
                'status' => 'RECEBIDO',
                'document_id' => $doc->id,
                'recebido_por' => $user->id,
                'tenant_id' => 1,
            ]);
        }
    }
}
