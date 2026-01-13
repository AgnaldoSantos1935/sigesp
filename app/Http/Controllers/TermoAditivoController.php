<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\BaseDataTableController;
use App\Models\TermoAditivo;
use App\Models\InstrumentoJuridico;

use Illuminate\Support\Facades\Gate;

class TermoAditivoController extends BaseDataTableController
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
        return view('termos_aditivos.index');
    }

    public function create()
    {
        $instrumentos = InstrumentoJuridico::select('id', 'numero', 'objeto')->orderByDesc('id')->get();
        return view('termos_aditivos.create', compact('instrumentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instrumento_juridico_id' => 'required|exists:instrumentos_juridicos,id',
            'numero' => 'required|string|max:255',
            'processo' => 'nullable|string|max:255',
            'objeto' => 'required|string',
            'valor_ajuste' => 'nullable|numeric',
            'data_assinatura' => 'required|date',
            'data_publicacao' => 'nullable|date',
            'ajusta_vigencia_fim' => 'nullable|date',
            'ajusta_valor_global' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request) {
            TermoAditivo::create($request->only([
                'instrumento_juridico_id','numero','processo','objeto',
                'valor_ajuste','data_assinatura','data_publicacao'
            ]));

            $instrumento = InstrumentoJuridico::find($request->instrumento_juridico_id);
            if ($instrumento) {
                if ($request->filled('ajusta_vigencia_fim')) {
                    $instrumento->vigencia_fim = $request->ajusta_vigencia_fim;
                }
                if ($request->filled('ajusta_valor_global')) {
                    $instrumento->valor_global = $request->ajusta_valor_global;
                }
                $instrumento->save();
            }
        });

        return redirect()->route('termos_aditivos.index')->with('success', 'Termo Aditivo registrado.');
    }

    protected function query(): Builder
    {
        return TermoAditivo::query()->with('instrumento');
    }

    protected function actions($row): string
    {
        return view('termos_aditivos.partials.actions', compact('row'))->render();
    }
}

