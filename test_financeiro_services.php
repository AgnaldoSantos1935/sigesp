<?php

use App\Models\Contrato;
use App\Models\ContratoItem;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Empenho;
use App\Models\InstrumentoJuridico;
use App\Models\Tenant;
use App\Models\User;
use App\Services\EmpenhoService;
use App\Services\PagamentoService;
use Illuminate\Validation\ValidationException;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate Tenant Context
$tenant = Tenant::first();
if (!$tenant) {
    die("No tenant found. Run migrations/seeds first.\n");
}
session(['tenant_id' => $tenant->id]);
auth()->login(User::first() ?? User::factory()->create(['tenant_id' => $tenant->id]));

echo "Testando Services Financeiros para Tenant: {$tenant->nome}\n";

$empenhoService = new EmpenhoService();
$pagamentoService = new PagamentoService($empenhoService);

try {
    // 1. Setup Data
    echo "1. Configurando dados de teste...\n";
    
    // Create Types if not exist
    $neType = DocumentType::firstOrCreate(['slug' => 'nota_empenho'], ['name' => 'Nota de Empenho']);
    $obType = DocumentType::firstOrCreate(['slug' => 'ordem_bancaria'], ['name' => 'Ordem Bancária']);
    $nfType = DocumentType::firstOrCreate(['slug' => 'nota_fiscal'], ['name' => 'Nota Fiscal']);
    
    // Create Dummy Contract Item
    $instrumento = InstrumentoJuridico::first();
    if (!$instrumento) {
        // Fallback creation if seeders didn't run
        // Assuming they did based on T12
        die("Instrumento Jurídico not found.\n");
    }

    $contrato = Contrato::firstOrCreate(
        ['numero' => '999/2025', 'tenant_id' => $tenant->id],
        [
            'instrumento_juridico_id' => $instrumento->id,
            'ano' => 2025,
            'objeto' => 'Contrato de Teste Financeiro',
            'fornecedor_nome' => 'Teste Ltda',
            'fornecedor_cnpj' => '00.000.000/0001-00',
            'tipo_contrato' => 'AQUISICAO',
            'status' => 'ATIVO'
        ]
    );

    $item = ContratoItem::create([
        'contrato_id' => $contrato->id,
        'descricao' => 'Item de Teste Financeiro',
        'unidade_medida' => 'UN',
        'quantidade_contratada' => 10,
        'valor_unitario' => 1000.00,
        'valor_total' => 10000.00, // 10k total
        'controle_execucao' => 'GLOBAL',
        'tenant_id' => $tenant->id
    ]);

    // Create Document NE
    $docNE = Document::create([
        'document_type_id' => $neType->id,
        'numero' => 'NE-TEST-001',
        'data_emissao' => now(),
        'descricao_resumida' => 'NE Teste',
        'status' => 'VALID',
        'tenant_id' => $tenant->id
    ]);

    // 2. Test Empenho Creation
    echo "2. Testando criação de Empenho...\n";
    $empenhoData = [
        'contrato_item_id' => $item->id,
        'numero' => '2025NE999',
        'ano' => 2025,
        'data_emissao' => now(),
        'valor' => 5000.00, // 50% do item
        'descricao' => 'Empenho de Teste',
        'document_id' => $docNE->id
    ];

    $empenho = $empenhoService->createEmpenho($empenhoData);
    echo "   ✅ Empenho criado: ID {$empenho->id}, Valor: {$empenho->valor}\n";

    // 3. Test Duplicate Empenho (Should Fail)
    echo "3. Testando bloqueio de duplicidade...\n";
    try {
        $empenhoService->createEmpenho($empenhoData);
        echo "   ❌ FALHA: Permitiu empenho duplicado.\n";
    } catch (ValidationException $e) {
        echo "   ✅ Sucesso: Bloqueou duplicidade ({$e->getMessage()}).\n";
    }

    // 4. Test Balance Calculation (Item)
    echo "4. Validando saldo do item...\n";
    $saldoItem = $empenhoService->calcularSaldoDisponivelItem($item); // Should be 5000
    if ($saldoItem == 5000.00) {
        echo "   ✅ Saldo Item correto: {$saldoItem}\n";
    } else {
        echo "   ❌ Saldo Item incorreto: {$saldoItem} (Esperado 5000.00)\n";
    }

    // 5. Test Payment
    echo "5. Testando Pagamento...\n";
    
    // Create Docs
    $docOB = Document::create([
        'document_type_id' => $obType->id,
        'numero' => 'OB-TEST-001',
        'data_emissao' => now(),
        'descricao_resumida' => 'OB Teste',
        'status' => 'VALID',
        'tenant_id' => $tenant->id
    ]);
    
    $docNF = Document::create([
        'document_type_id' => $nfType->id,
        'numero' => 'NF-TEST-001',
        'data_emissao' => now(),
        'descricao_resumida' => 'NF Teste',
        'status' => 'VALID',
        'tenant_id' => $tenant->id
    ]);

    $pagamentoData = [
        'empenho_id' => $empenho->id,
        'numero_ordem_bancaria' => 'OB999',
        'data_pagamento' => now(),
        'valor' => 2000.00,
        'document_id' => $docOB->id,
        'nota_fiscal_id' => $docNF->id
    ];

    $pagamento = $pagamentoService->registrarPagamento($pagamentoData);
    echo "   ✅ Pagamento registrado: ID {$pagamento->id}, Valor: {$pagamento->valor}\n";

    // 6. Test Payment Exceeding Balance
    echo "6. Testando pagamento excedente...\n";
    try {
        $pagamentoService->registrarPagamento([
            'empenho_id' => $empenho->id,
            'data_pagamento' => now(),
            'valor' => 4000.00, // Remaining is 3000
            'document_id' => $docOB->id,
            'nota_fiscal_id' => $docNF->id
        ]);
        echo "   ❌ FALHA: Permitiu pagamento excedente.\n";
    } catch (ValidationException $e) {
        echo "   ✅ Sucesso: Bloqueou pagamento excedente ({$e->getMessage()}).\n";
    }

    // 7. Verify Final Balance
    $saldoEmpenho = $empenhoService->calcularSaldoDisponivelEmpenho($empenho); // Should be 3000
    echo "7. Saldo Final Empenho: {$saldoEmpenho}\n";
    
    if ($saldoEmpenho == 3000.00) {
        echo "   ✅ TUDO OK!\n";
    } else {
        echo "   ❌ Saldo Final incorreto.\n";
    }

    // Clean up (optional, relying on fresh migrate for next run)
    // $item->delete(); $contrato->delete();

} catch (Exception $e) {
    echo "❌ ERRO GERAL: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
