<?php

namespace App\Http\Controllers;

use App\Models\PessoaJuridica;
use App\Models\Endereco;
use App\Models\Contato;
use App\Models\PessoaFisica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PessoasJuridicasController extends BaseDataTableController
{
    public function index()
    {
        return view('pessoas_juridicas.index');
    }

    public function create()
    {
        // Busca todas as pessoas físicas para o select de representante
        $pessoasFisicas = PessoaFisica::where('ativo', true)->select('id', 'nome_completo', 'cpf')->get();
        return view('pessoas_juridicas.create', compact('pessoasFisicas'));
    }

    public function store(Request $request)
    {
        // Validação básica
        $request->validate([
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:pessoas_juridicas,cnpj',
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'uf' => 'required|string|max:2',
            // Validação do representante
            'representante_id' => 'nullable|exists:pessoas_fisicas,id',
            'representante_tipo' => 'nullable|in:LEGAL,PROCURADOR,TECNICO',
            'representante_inicio' => 'nullable|required_with:representante_id|date',
        ]);

        try {
            DB::beginTransaction();

            $endereco = Endereco::create($request->only([
                'cep', 'logradouro', 'numero', 'bairro', 'municipio', 'estado', 'uf', 'pais', 'complemento'
            ]));

            $contato = Contato::create($request->only([
                'email_1', 'email_2', 'telefone_fixo', 'celular_1', 'celular_2', 'rede_social_1', 'rede_social_2'
            ]));

            $pj = PessoaJuridica::create([
                'razao_social' => $request->razao_social,
                'nome_fantasia' => $request->nome_fantasia,
                'cnpj' => $request->cnpj,
                'cod_cnae' => $request->cod_cnae,
                'ramo_atividade' => $request->ramo_atividade,
                'cod_natjuridica' => $request->cod_natjuridica,
                'tipo_pessoa' => $request->tipo_pessoa,
                'endereco_id' => $endereco->id,
                'contato_id' => $contato->id,
                'ativo' => true,
            ]);

            // Vincular Representante se selecionado
            if ($request->filled('representante_id')) {
                $pj->representantes()->attach($request->representante_id, [
                    'tipo' => $request->representante_tipo,
                    'inicio_vigencia' => $request->representante_inicio,
                    'fim_vigencia' => $request->representante_fim, // Opcional
                ]);
            }

            DB::commit();

            return redirect()->route('pessoas_juridicas.index')
                ->with('success', 'Pessoa Jurídica cadastrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage())->withInput();
        }
    }

    protected function query(): Builder
    {
        return PessoaJuridica::query()->with(['endereco']);
    }

    protected function actions($row): string
    {
        return view('pessoas_juridicas.partials.actions', compact('row'))->render();
    }
}
