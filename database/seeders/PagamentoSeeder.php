<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagamento;
use App\Models\Empenho;
use App\Models\Document;
use App\Models\DocumentType;
use Carbon\Carbon;

class PagamentoSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $nfType = DocumentType::firstOrCreate(['slug' => 'nota_fiscal'], ['name' => 'Nota Fiscal']);
        $obType = DocumentType::firstOrCreate(['slug' => 'ordem_bancaria'], ['name' => 'Ordem Bancária']);

        $empenhos = Empenho::all();

        foreach ($empenhos as $index => $empenho) {
            // Pay 50% of the empenho value to leave balance
            $valorPagamento = $empenho->valor * 0.5;

            // 1. Create NF (Valid)
            $nf = Document::create([
                'document_type_id' => $nfType->id,
                'numero' => 'NF-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'data_emissao' => $empenho->data_emissao->addDays(5),
                'descricao_resumida' => 'NF referente ao empenho ' . $empenho->numero,
                'status' => 'VALID', // Required for service/logic
                'tenant_id' => 1,
            ]);

            // 2. Create OB (Proof)
            $ob = Document::create([
                'document_type_id' => $obType->id,
                'numero' => '2024OB' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'data_emissao' => $nf->data_emissao->addDays(2),
                'descricao_resumida' => 'Pagamento da NF ' . $nf->numero,
                'status' => 'VALID',
                'tenant_id' => 1,
            ]);

            // 3. Create Pagamento
            Pagamento::create([
                'empenho_id' => $empenho->id,
                'numero_ordem_bancaria' => $ob->numero,
                'data_pagamento' => $ob->data_emissao,
                'valor' => $valorPagamento,
                'status' => 'PAGO',
                'document_id' => $ob->id,
                'nota_fiscal_id' => $nf->id,
                'observacao' => 'Pagamento parcial (50%)',
                'tenant_id' => 1,
            ]);
        }
    }
}
