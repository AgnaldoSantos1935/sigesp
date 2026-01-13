<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Avaria;
use App\Models\Unidade;
use App\Services\DocumentService;
use App\Models\DocumentType;
use App\Models\Document;

class AvariaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('access-module', 'nt');
            return $next($request);
        });
    }

    public function index()
    {
        $avarias = Avaria::latest()->paginate(10);
        return view('avarias.index', compact('avarias'));
    }

    public function create()
    {
        $unidades = Unidade::all();
        return view('avarias.create', compact('unidades'));
    }

    public function store(Request $request, DocumentService $documentService)
    {
        $request->validate([
            'unidade_id' => 'required|exists:unidades,id',
            'equipamento' => 'required|string|max:255',
            'descricao_problema' => 'required|string',
            'prioridade' => 'required|string',
            'arquivo_documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status'] = 'ABERTO';

        if ($request->hasFile('arquivo_documento')) {
            $type = DocumentType::firstOrCreate(['slug' => 'laudo-tecnico'], ['name' => 'Laudo Técnico / Evidência']);

            $doc = $documentService->createDocument([
                'document_type_id' => $type->id,
                'numero' => 'AVARIA-' . time(), // Temporário
                'data_emissao' => now(),
                'descricao_resumida' => 'Evidência de avaria: ' . $data['equipamento'],
            ]);

            $documentService->uploadFile($doc, $request->file('arquivo_documento'));
            
            // Validar documento automaticamente
            $documentService->changeStatus($doc, Document::STATUS_VALID);

            $data['document_id'] = $doc->id;
        }

        Avaria::create($data);

        return redirect()->route('avarias.index')->with('success', 'Avaria registrada com sucesso.');
    }
}
