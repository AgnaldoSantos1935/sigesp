<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\BaseDataTableController;
use App\Models\Apostilamento;
use App\Models\InstrumentoJuridico;

use Illuminate\Support\Facades\Gate;

class ApostilamentoController extends BaseDataTableController
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
        return view('apostilamentos.index');
    }

    public function create()
    {
        $instrumentos = InstrumentoJuridico::select('id', 'numero', 'objeto')->orderByDesc('id')->get();
        return view('apostilamentos.create', compact('instrumentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instrumento_juridico_id' => 'required|exists:instrumentos_juridicos,id',
            'numero' => 'required|string|max:255',
            'processo' => 'nullable|string|max:255',
            'objeto' => 'required|string',
            'data_publicacao' => 'required|date',
            'data_assinatura' => 'nullable|date',
        ]);

        Apostilamento::create($request->all());
        return redirect()->route('apostilamentos.index')->with('success', 'Apostilamento registrado.');
    }

    protected function query(): Builder
    {
        return Apostilamento::query()->with('instrumento');
    }

    protected function actions($row): string
    {
        return view('apostilamentos.partials.actions', compact('row'))->render();
    }
}

