<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\BaseDataTableController;
use App\Models\InstrumentoJuridico;
use App\Models\PessoaJuridica;
use App\Models\Licitacao;
use App\Models\PessoaFisica;

use Illuminate\Support\Facades\Gate;

class InstrumentoJuridicoController extends BaseDataTableController
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
        $tipo = request()->route('tipo');
        $titulo = match($tipo) {
            'contrato' => 'Contratos',
            'convenio' => 'Convênios',
            default => 'Instrumentos Jurídicos'
        };
        return view('instrumentos.index', compact('tipo', 'titulo'));
    }

    public function create()
    {
        $tipo = request()->route('tipo');
        $titulo = match($tipo) {
            'contrato' => 'Novo Contrato',
            'convenio' => 'Novo Convênio',
            default => 'Novo Instrumento Jurídico'
        };

        $fornecedores = PessoaJuridica::select('id', 'razao_social', 'cnpj')->orderBy('razao_social')->get();
        $licitacoes = Licitacao::select('id', 'numero_licitacao', 'modalidade')->orderByDesc('id')->get();
        $prepostos = PessoaFisica::select('id', 'nome_completo')->orderBy('nome_completo')->get();
        return view('instrumentos.create', compact('fornecedores', 'licitacoes', 'prepostos', 'tipo', 'titulo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pessoa_juridica_id' => 'required|exists:pessoas_juridicas,id',
            'licitacao_id' => 'nullable|exists:licitacoes,id',
            'preposto_id' => 'nullable|exists:pessoas_fisicas,id',
            'tipo' => 'nullable|string|max:100',
            'categoria' => 'nullable|string|max:100',
            'numero' => 'nullable|string|max:255',
            'processo_administrativo' => 'nullable|string|max:255',
            'objeto' => 'required|string',
            'valor_global' => 'nullable|numeric',
            'vigencia_inicio' => 'nullable|date',
            'vigencia_fim' => 'nullable|date',
            'data_assinatura' => 'nullable|date',
            'data_publicacao' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {
            InstrumentoJuridico::create($request->all());
        });

        $tipo = $request->tipo;
        if ($tipo === 'contrato') {
            return redirect()->route('contratos.index')->with('success', 'Contrato cadastrado.');
        } elseif ($tipo === 'convenio') {
            return redirect()->route('convenios.index')->with('success', 'Convênio cadastrado.');
        }

        return redirect()->route('instrumentos.index')->with('success', 'Instrumento Jurídico cadastrado.');
    }

    protected function query(): Builder
    {
        $query = InstrumentoJuridico::query()->with('designacoes');

        if ($tipo = request()->route('tipo')) {
            $query->where('tipo', $tipo);
        }

        return $query;
    }

    protected function actions($row): string
    {
        return view('instrumentos.partials.actions', compact('row'))->render();
    }
}

