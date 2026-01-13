<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\BaseDataTableController;
use App\Models\Licitacao;

class LicitacaoController extends BaseDataTableController
{
    public function index()
    {
        return view('licitacoes.index');
    }

    public function create()
    {
        return view('licitacoes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'modalidade' => 'required|string|max:255',
            'objeto' => 'required|string|max:500',
            'numero_licitacao' => 'nullable|string|max:255',
            'numero_processo' => 'nullable|string|max:255',
            'numero_edital' => 'nullable|string|max:255',
            'criterios' => 'nullable|string|max:255',
            'habilitacao' => 'nullable|string|max:255',
            'fundamento_legal' => 'nullable|string|max:255',
            'data_publicacao' => 'nullable|date',
            'data_encerramento' => 'nullable|date',
        ]);

        Licitacao::create($request->all());
        return redirect()->route('licitacoes.index')->with('success', 'Licitação cadastrada.');
    }

    protected function query(): Builder
    {
        return Licitacao::query();
    }

    protected function actions($row): string
    {
        return view('licitacoes.partials.actions', compact('row'))->render();
    }
}

