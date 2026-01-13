<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Pagamento;
use App\Models\Empenho;
use App\Models\Document;
use App\Models\DocumentType;
use App\Services\PagamentoService;
use App\Services\DocumentService;

class PagamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('access-module', 'financeiro');
            return $next($request);
        });
    }

    public function index()
    {
        $pagamentos = Pagamento::with('empenho')->latest('data_pagamento')->paginate(10);
        return view('pagamentos.index', compact('pagamentos'));
    }

    public function create()
    {
        // Apenas empenhos ATIVOS podem receber pagamento
        $empenhos = Empenho::where('status', Empenho::STATUS_ATIVO)->get();
        return view('pagamentos.create', compact('empenhos'));
    }

    public function store(Request $request, PagamentoService $pagamentoService, DocumentService $documentService)
    {
        $request->validate([
            'empenho_id' => 'required|exists:empenhos,id',
            'data_pagamento' => 'required|date',
            'valor' => 'required|numeric|min:0.01',
            'arquivo_ob' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Ordem Bancária
            'arquivo_nf' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Nota Fiscal / Ateste
            'numero_ordem_bancaria' => 'nullable|string',
            'observacao' => 'nullable|string',
        ]);

        $data = $request->all();

        // 1. Criar Documento OB (Ordem Bancária)
        if ($request->hasFile('arquivo_ob')) {
            $typeOb = DocumentType::firstOrCreate(['slug' => 'ordem_bancaria'], ['name' => 'Ordem Bancária']);
            
            $docOb = $documentService->createDocument([
                'document_type_id' => $typeOb->id,
                'numero' => $data['numero_ordem_bancaria'] ?? 'OB-AUTO-' . time(),
                'data_emissao' => $data['data_pagamento'],
                'descricao_resumida' => 'Ordem Bancária referente ao pagamento.',
            ]);
            
            $documentService->uploadFile($docOb, $request->file('arquivo_ob'));
            $documentService->changeStatus($docOb, Document::STATUS_VALID);
            
            $data['document_id'] = $docOb->id;
        }

        // 2. Criar Documento NF (Nota Fiscal/Ateste)
        if ($request->hasFile('arquivo_nf')) {
            $typeNf = DocumentType::firstOrCreate(['slug' => 'nota_fiscal'], ['name' => 'Nota Fiscal']);
            
            $docNf = $documentService->createDocument([
                'document_type_id' => $typeNf->id,
                'numero' => 'NF-AUTO-' . time(),
                'data_emissao' => $data['data_pagamento'], // Assumindo data do pagamento se não informado
                'descricao_resumida' => 'Nota Fiscal / Comprovante de Ateste.',
            ]);
            
            $documentService->uploadFile($docNf, $request->file('arquivo_nf'));
            $documentService->changeStatus($docNf, Document::STATUS_VALID);
            
            $data['nota_fiscal_id'] = $docNf->id;
        }

        // 3. Registrar Pagamento via Service
        $pagamentoService->registrarPagamento($data);

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento registrado com sucesso.');
    }
}
