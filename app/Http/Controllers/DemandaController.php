<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Demanda;
use App\Services\DemandaService;
use App\Services\DocumentService;
use App\Models\DocumentType;
use App\Models\Document;

class DemandaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('access-module', 'fabrica_software');
            return $next($request);
        });
    }

    public function index()
    {
        $demandas = Demanda::latest()->paginate(10);
        return view('demandas.index', compact('demandas'));
    }

    public function create()
    {
        return view('demandas.create');
    }

    public function store(Request $request, DemandaService $service, DocumentService $documentService)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|string',
            'arquivo_documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $data = $request->all();
        $data['status'] = 'ENVIADA';

        if ($request->hasFile('arquivo_documento')) {
            $type = DocumentType::firstOrCreate(['slug' => 'anexo-demanda'], ['name' => 'Anexo de Demanda']);

            $doc = $documentService->createDocument([
                'document_type_id' => $type->id,
                'numero' => 'ANEXO-' . time(),
                'data_emissao' => now(),
                'descricao_resumida' => 'Anexo da demanda: ' . $data['titulo'],
            ]);

            $documentService->uploadFile($doc, $request->file('arquivo_documento'));
            
            // Validar documento automaticamente
            $documentService->changeStatus($doc, Document::STATUS_VALID);

            $data['document_id'] = $doc->id;
        }

        // Quem está criando é o usuário atual (User)
        // Como o Service espera Model $demandante, passamos o User logado.
        // Se no futuro for Unidade, alteramos a lógica.
        $service->createDemanda($data, auth()->user());

        return redirect()->route('demandas.index')->with('success', 'Demanda enviada com sucesso.');
    }
}
