<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\ContratoItem;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ContratoItemSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) {
            return;
        }

        // 1. Dell Items
        $ctDell = Contrato::where('numero', '001/2025')->where('tenant_id', $tenant->id)->first();
        if ($ctDell) {
            ContratoItem::firstOrCreate(
                ['contrato_id' => $ctDell->id, 'descricao' => 'Notebook Core i7 16GB'],
                [
                    'unidade_medida' => 'UN',
                    'quantidade_contratada' => 50,
                    'valor_unitario' => 6000.00,
                    'valor_total' => 300000.00,
                    'controle_execucao' => 'POR_ITEM',
                    'tenant_id' => $tenant->id
                ]
            );
            ContratoItem::firstOrCreate(
                ['contrato_id' => $ctDell->id, 'descricao' => 'Monitor 27 Polegadas'],
                [
                    'unidade_medida' => 'UN',
                    'quantidade_contratada' => 100,
                    'valor_unitario' => 2000.00,
                    'valor_total' => 200000.00,
                    'controle_execucao' => 'POR_ITEM',
                    'tenant_id' => $tenant->id
                ]
            );
        }

        // 2. Internet Items
        $ctNet = Contrato::where('numero', '005/2025')->where('tenant_id', $tenant->id)->first();
        if ($ctNet) {
            ContratoItem::firstOrCreate(
                ['contrato_id' => $ctNet->id, 'descricao' => 'Link Dedicado 1Gbps'],
                [
                    'unidade_medida' => 'MES',
                    'quantidade_contratada' => 12,
                    'valor_unitario' => 10000.00,
                    'valor_total' => 120000.00,
                    'controle_execucao' => 'GLOBAL',
                    'tenant_id' => $tenant->id
                ]
            );
        }

        // 3. Factory Items
        $ctSoft = Contrato::where('numero', '020/2025')->where('tenant_id', $tenant->id)->first();
        if ($ctSoft) {
            ContratoItem::firstOrCreate(
                ['contrato_id' => $ctSoft->id, 'descricao' => 'UST - Unidade de Serviço Técnico'],
                [
                    'unidade_medida' => 'UST',
                    'quantidade_contratada' => 5000,
                    'valor_unitario' => 400.00,
                    'valor_total' => 2000000.00,
                    'controle_execucao' => 'GLOBAL',
                    'tenant_id' => $tenant->id
                ]
            );
        }
    }
}
