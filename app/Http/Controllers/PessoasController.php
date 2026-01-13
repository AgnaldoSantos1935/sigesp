<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PessoaFisica;
use App\Models\Endereco;
use App\Models\Contato;
use App\Models\User;
use App\Http\Requests\PessoaFisicaRequest;
use App\Http\Controllers\BaseDataTableController;
use Illuminate\Database\Eloquent\Builder;



class PessoasController extends BaseDataTableController
{
    public function index()
    {
        return view('pessoas.index');
    }
public function create()
{
    return view('pessoas.create');
}

    public function store(Request $request)
    {
        $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:pessoas_fisicas,cpf',
            'data_nascimento' => 'nullable|date',
            'genero' => 'nullable|string|max:255',
            'tipo_sanguineo' => 'nullable|string|max:10',
            'ativo' => 'nullable|boolean',
            'user.login' => 'nullable|string|max:255',
            'user.email' => 'nullable|email|max:255|unique:users,email',
            'user.password' => 'nullable|string|min:6',
            'contato.telefone' => 'nullable|string|max:255',
            'contato.celular' => 'nullable|string|max:255',
            'contato.email' => 'nullable|email|max:255',
            'endereco.cep' => 'nullable|string|max:10',
            'endereco.logradouro' => 'nullable|string|max:255',
            'endereco.numero' => 'nullable|string|max:255',
            'endereco.bairro' => 'nullable|string|max:255',
            'endereco.municipio' => 'nullable|string|max:255',
            'endereco.uf' => 'nullable|string|max:2',
            'endereco.pais' => 'nullable|string|max:255',
            'endereco.complemento' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $userId = null;
            if ($request->filled('user.email') && $request->filled('user.password')) {
                $user = User::create([
                    'name' => $request->input('user.login') ?: $request->input('nome_completo'),
                    'email' => $request->input('user.email'),
                    'password' => Hash::make($request->input('user.password')),
                ]);
                $userId = $user->id;
            }

            $enderecoId = null;
            if ($request->has('endereco') && $request->filled('endereco.cep')) {
                $endereco = Endereco::create([
                    'cep' => $request->input('endereco.cep'),
                    'logradouro' => $request->input('endereco.logradouro'),
                    'numero' => $request->input('endereco.numero'),
                    'bairro' => $request->input('endereco.bairro'),
                    'municipio' => $request->input('endereco.municipio'),
                    'estado' => $request->input('endereco.estado'),
                    'uf' => $request->input('endereco.uf'),
                    'pais' => $request->input('endereco.pais', 'Brasil'),
                    'complemento' => $request->input('endereco.complemento'),
                ]);
                $enderecoId = $endereco->id;
            }

            $pf = PessoaFisica::create([
                'user_id' => $userId,
                'endereco_id' => $enderecoId,
                'nome_completo' => $request->input('nome_completo'),
                'cpf' => $request->input('cpf'),
                'rg' => $request->input('rg'),
                'data_nascimento' => $request->input('data_nascimento'),
                'genero' => $request->input('genero'),
                'tipo_sanguineo' => $request->input('tipo_sanguineo'),
                'ativo' => (bool) $request->input('ativo', 1),
            ]);

            if ($request->has('contato')) {
                Contato::create([
                    'email_1' => $request->input('contato.email'),
                    'telefone_fixo' => $request->input('contato.telefone'),
                    'celular_1' => $request->input('contato.celular'),
                    'pessoa_fisica_id' => $pf->id,
                ]);
            }

            DB::commit();

            return redirect()->route('pessoas.index')->with('success', 'Pessoa cadastrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage())->withInput();
        }
    }
    protected function query(): Builder
    {
        return PessoaFisica::query()
            ->select('id', 'nome_completo', 'cpf', 'ativo');
    }

    protected function actions($pessoa): string
    {
        return view('pessoas.partials.actions', compact('pessoa'))->render();
    }


}
