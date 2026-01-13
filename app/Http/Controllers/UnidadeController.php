<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use App\Models\Dre;
use App\Models\Document;
use App\Services\UnidadeVinculoAdministrativoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnidadeController extends Controller
{
    protected $vinculoService;

    public function __construct(UnidadeVinculoAdministrativoService $vinculoService)
    {
        $this->vinculoService = $vinculoService;
    }

    public function index()
    {
        Gate::authorize('viewAny', Unidade::class);
        
        $unidades = Unidade::with(['dre', 'vinculoAtual'])->paginate(15);
        return view('unidades.index', compact('unidades'));
    }

    public function show(Unidade $unidade)
    {
        Gate::authorize('view', $unidade);
        
        $unidade->load(['dre', 'vinculosAdministrativos.creator', 'vinculosAdministrativos.document']);
        return view('unidades.show', compact('unidade'));
    }

    public function updateVinculo(Request $request, Unidade $unidade)
    {
        Gate::authorize('changeVinculo', $unidade);

        $request->validate([
            'dre_id' => 'required|exists:dres,id',
            'dirigente_nome' => 'required|string|max:255',
            'dirigente_cargo' => 'required|string|max:255',
            'document_id' => 'required|exists:documents,id',
            'data_inicio' => 'required|date',
        ]);

        try {
            $this->vinculoService->changeVinculo($unidade, $request->all());
            
            return redirect()
                ->route('unidades.show', $unidade)
                ->with('success', 'Vínculo administrativo atualizado com sucesso.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
