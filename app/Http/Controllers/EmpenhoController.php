<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Empenho;
use App\Models\ContratoItem;
use App\Services\EmpenhoService;
use App\Services\DocumentService;
use App\Models\DocumentType;
use App\Models\Document;

class EmpenhoController extends Controller
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
        $empenhos = Empenho::latest()->paginate(10);
        return view('empenhos.index', compact('empenhos'));
    }

    public function create()
    {
        $contratoItens = ContratoItem::all();
        return view('empenhos.create', compact('contratoItens'));
    }

    public function store(Request $request, EmpenhoService $empenhoService, DocumentService $documentService)
    {
        $request->validate([
            'contrato_item_id' => 'required|exists:contrato_itens,id',
            'numero' => 'required|string|max:255',
            'ano' => 'required|integer',
            'data_emissao' => 'required|date',
            'valor' => 'required|numeric',
            'arquivo_documento' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        $data = $request->all();

        // 1. Criar Documento (Nota de Empenho)
        if ($request->hasFile('arquivo_documento')) {
            $type = DocumentType::firstOrCreate(['slug' => 'nota-empenho'], ['name' => 'Nota de Empenho']);

            $doc = $documentService->createDocument([
                'document_type_id' => $type->id,
                'numero' => 'NE ' . $data['numero'] . '/' . $data['ano'],
                'data_emissao' => $data['data_emissao'],
                'descricao_resumida' => 'Nota de Empenho ' . $data['numero'] . ' gerada no módulo Financeiro.',
            ]);

            $documentService->uploadFile($doc, $request->file('arquivo_documento'));
            
            // Aprovar automaticamente pois é um documento oficial gerado
            $documentService->changeStatus($doc, Document::STATUS_VALID);

            $data['document_id'] = $doc->id;
        }

        $empenhoService->createEmpenho($data);

        return redirect()->route('empenhos.index')->with('success', 'Empenho criado com sucesso.');
    }
}
