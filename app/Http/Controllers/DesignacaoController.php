<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\BaseDataTableController;
use App\Models\Designacao;
use App\Models\InstrumentoJuridico;
use App\Models\PessoaFisica;
use App\Models\Cargo;

use Illuminate\Support\Facades\Gate;

class DesignacaoController extends BaseDataTableController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Gate::authorize('access-module', 'contratos');
            return $next($request);
        });
    }

    public function index()
    {
        return view('designacoes.index');
    }

    public function create()
    {
        $instrumentos = InstrumentoJuridico::select('id','numero','objeto')->orderByDesc('id')->get();
        $pessoas = PessoaFisica::select('id','nome_completo')->orderBy('nome_completo')->get();
        $cargos = Cargo::select('id','nome')->orderBy('nome')->get();
        return view('designacoes.create', compact('instrumentos','pessoas','cargos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instrumento_juridico_id' => 'required|exists:instrumentos_juridicos,id',
            'gestor_id' => 'nullable|exists:pessoas_fisicas,id',
            'fiscal_adm_id' => 'nullable|exists:pessoas_fisicas,id',
            'fiscal_tecnico_id' => 'nullable|exists:pessoas_fisicas,id',
            'suplente_adm_id' => 'nullable|exists:pessoas_fisicas,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'portaria' => 'nullable|string|max:255',
            'diario_oficial' => 'nullable|string|max:255',
            'data_publicacao' => 'nullable|date',
            'a_contar' => 'nullable|date',
            'funcao' => 'nullable|string|max:255',
            'descricao_funcao' => 'nullable|string|max:255',
        ]);

        Designacao::create($request->all());
        return redirect()->route('designacoes.index')->with('success', 'Designação registrada.');
    }

    protected function query(): Builder
    {
        return Designacao::query()->with(['instrumento', 'gestor', 'fiscalAdm', 'fiscalTecnico']);
    }

    protected function actions($row): string
    {
        return view('designacoes.partials.actions', compact('row'))->render();
    }
}

