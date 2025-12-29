<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PessoaFisica;
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
