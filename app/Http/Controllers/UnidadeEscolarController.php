<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseDataTableController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\UnidadeEscolar;
use App\Models\Regional;
use App\Models\PessoaFisica;
use App\Models\Endereco;
use App\Models\Contato;
use App\Models\ChefiaEscolar;

class UnidadeEscolarController extends BaseDataTableController
{
    public function index()
    {
        return view('unidades.index');
    }

    public function create()
    {
        $regionais = Regional::all();
        $pessoasFisicas = PessoaFisica::where('ativo', true)->select('id', 'nome_completo', 'cpf')->get();
        return view('unidades.create', compact('regionais', 'pessoasFisicas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'inep' => 'nullable|string|max:12|unique:unidades_escolares,codigo_inep',
            'regional_id' => 'nullable|exists:regionais,id',
            'municipio' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'uf' => 'required|string|max:2',
            'gestor_id' => 'nullable|exists:pessoas_fisicas,id',
        ]);

        try {
            DB::beginTransaction();

            $endereco = Endereco::create($request->only([
                'cep', 'logradouro', 'numero', 'bairro', 'municipio', 'estado', 'uf', 'pais', 'complemento'
            ]));

            $contato = Contato::create($request->only([
                'email_1', 'email_2', 'telefone_fixo', 'celular_1', 'celular_2'
            ]));

            $chefiaId = null;
            if ($request->filled('gestor_id')) {
                $chefia = ChefiaEscolar::create([
                    'pessoa_fisica_id' => $request->gestor_id,
                    'data_inicio' => now(), // Assume início imediato
                    'titulo' => 'Diretor Escolar' // Título padrão, pode ser ajustado
                ]);
                $chefiaId = $chefia->id;
            }

            UnidadeEscolar::create([
                'nome_escola' => $request->nome,
                'codigo_inep' => $request->inep,
                'municipio' => $request->municipio,
                'uf' => $request->uf,
                'endereco' => $request->logradouro . ', ' . $request->numero . ' - ' . $request->bairro,
                'regional_id' => $request->regional_id,
                'endereco_id' => $endereco->id,
                'contato_id' => $contato->id,
                'chefia_escolar_id' => $chefiaId,
            ]);

            DB::commit();

            return redirect()->route('unidades.index')
                ->with('success', 'Unidade Escolar cadastrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage())->withInput();
        }
    }

    protected function query(): Builder
    {
        return UnidadeEscolar::query()
            ->with(['regional', 'endereco', 'chefia.pessoaFisica']);
    }

    protected function actions($row): string
    {
        return view('unidades.partials.actions', compact('row'))->render();
    }

    public function show($id)
    {
        $unidade = UnidadeEscolar::findOrFail($id);
        return view('unidades.show', compact('unidade'));
    }

    public function edit($id)
    {
        $unidade = UnidadeEscolar::findOrFail($id);
        $regionais = Regional::all();
        $pessoasFisicas = PessoaFisica::where('ativo', true)->select('id', 'nome_completo', 'cpf')->get();
        return view('unidades.edit', compact('unidade', 'regionais', 'pessoasFisicas'));
    }

    public function update(Request $request, $id)
    {
        // TODO: Implementar atualização
        return redirect()->route('unidades.index')->with('warning', 'Funcionalidade de edição em desenvolvimento.');
    }

    public function destroy($id)
    {
        try {
            $unidade = UnidadeEscolar::findOrFail($id);
            $unidade->delete();
            return redirect()->route('unidades.index')->with('success', 'Unidade excluída com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Erro ao excluir: ' . $e->getMessage());
        }
    }
}
