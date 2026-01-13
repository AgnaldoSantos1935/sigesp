<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstrumentoJuridico;
use App\Models\InstrumentoJuridicoItem;
use App\Models\UnidadeEscolar;
use App\Models\UnidadeAdministrativa;
use Illuminate\Support\Facades\DB;

class InstrumentoJuridicoItemController extends Controller
{
    public function index($instrumentoId)
    {
        $instrumento = InstrumentoJuridico::with(['items.unidadesEscolares', 'items.unidadesAdministrativas'])
            ->findOrFail($instrumentoId);
        
        return view('instrumentos.items.index', compact('instrumento'));
    }

    public function create($instrumentoId)
    {
        $instrumento = InstrumentoJuridico::findOrFail($instrumentoId);
        $escolas = UnidadeEscolar::select('id_escola', 'nome_escola')->orderBy('nome_escola')->get();
        $adms = UnidadeAdministrativa::select('id', 'nome')->orderBy('nome')->get();
        
        return view('instrumentos.items.create', compact('instrumento', 'escolas', 'adms'));
    }

    public function store(Request $request, $instrumentoId)
    {
        $request->validate([
            'numero_item' => 'nullable|string',
            'descricao' => 'required|string',
            'unidade_medida' => 'nullable|string',
            'quantidade_total' => 'required|numeric',
            'valor_unitario' => 'required|numeric',
            'unidades_escolares' => 'nullable|array',
            'unidades_administrativas' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $item = new InstrumentoJuridicoItem($request->only([
                'numero_item', 'descricao', 'unidade_medida', 'quantidade_total', 'valor_unitario'
            ]));
            $item->instrumento_juridico_id = $instrumentoId;
            $item->valor_total = $item->quantidade_total * $item->valor_unitario;
            $item->save();

            // Sync Unidades Escolares
            if ($request->has('unidades_escolares')) {
                $syncData = [];
                foreach ($request->unidades_escolares as $escolaId) {
                    // For now, distributing quantity equally or leaving null if not specified per unit
                    // Assuming simple association for now as per prompt "contemplar uma ou várias unidades"
                    $syncData[$escolaId] = ['quantidade' => null]; 
                }
                $item->unidadesEscolares()->sync($syncData);
            }

            // Sync Unidades Administrativas
            if ($request->has('unidades_administrativas')) {
                $syncData = [];
                foreach ($request->unidades_administrativas as $admId) {
                    $syncData[$admId] = ['quantidade' => null];
                }
                $item->unidadesAdministrativas()->sync($syncData);
            }

            DB::commit();

            return redirect()->route('instrumentos.items.index', $instrumentoId)
                ->with('success', 'Item adicionado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao adicionar item: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($instrumentoId, $itemId)
    {
        $instrumento = InstrumentoJuridico::findOrFail($instrumentoId);
        $item = InstrumentoJuridicoItem::with(['unidadesEscolares', 'unidadesAdministrativas'])
            ->where('instrumento_juridico_id', $instrumentoId)
            ->findOrFail($itemId);

        $escolas = UnidadeEscolar::select('id_escola', 'nome_escola')->orderBy('nome_escola')->get();
        $adms = UnidadeAdministrativa::select('id', 'nome')->orderBy('nome')->get();
        
        return view('instrumentos.items.edit', compact('instrumento', 'item', 'escolas', 'adms'));
    }

    public function update(Request $request, $instrumentoId, $itemId)
    {
        $request->validate([
            'numero_item' => 'nullable|string',
            'descricao' => 'required|string',
            'unidade_medida' => 'nullable|string',
            'quantidade_total' => 'required|numeric',
            'valor_unitario' => 'required|numeric',
            'unidades_escolares' => 'nullable|array',
            'unidades_administrativas' => 'nullable|array',
            'qtd_escolas' => 'nullable|array',
            'qtd_adms' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $item = InstrumentoJuridicoItem::where('instrumento_juridico_id', $instrumentoId)->findOrFail($itemId);
            
            $item->fill($request->only([
                'numero_item', 'descricao', 'unidade_medida', 'quantidade_total', 'valor_unitario'
            ]));
            $item->valor_total = $item->quantidade_total * $item->valor_unitario;
            $item->save();

            // Sync Unidades Escolares
            $syncDataEscolas = [];
            if ($request->has('unidades_escolares')) {
                foreach ($request->unidades_escolares as $escolaId) {
                    $qtd = $request->qtd_escolas[$escolaId] ?? null;
                    $syncDataEscolas[$escolaId] = ['quantidade' => $qtd]; 
                }
            }
            $item->unidadesEscolares()->sync($syncDataEscolas);

            // Sync Unidades Administrativas
            $syncDataAdms = [];
            if ($request->has('unidades_administrativas')) {
                foreach ($request->unidades_administrativas as $admId) {
                    $qtd = $request->qtd_adms[$admId] ?? null;
                    $syncDataAdms[$admId] = ['quantidade' => $qtd];
                }
            }
            $item->unidadesAdministrativas()->sync($syncDataAdms);

            DB::commit();

            return redirect()->route('instrumentos.items.index', $instrumentoId)
                ->with('success', 'Item atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar item: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($instrumentoId, $itemId)
    {
        $item = InstrumentoJuridicoItem::where('instrumento_juridico_id', $instrumentoId)->findOrFail($itemId);
        $item->delete();
        return back()->with('success', 'Item removido com sucesso.');
    }
}
