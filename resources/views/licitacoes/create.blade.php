@extends('adminlte::page')

@section('title', 'Nova Licitação')

@section('content_header')
    <h1>Nova Licitação</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('licitacoes.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label>Número</label>
                        <input type="text" name="numero_licitacao" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Processo</label>
                        <input type="text" name="numero_processo" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Edital</label>
                        <input type="text" name="numero_edital" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Modalidade</label>
                        <input type="text" name="modalidade" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label>Objeto</label>
                        <input type="text" name="objeto" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Critérios</label>
                        <input type="text" name="criterios" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Habilitação</label>
                        <input type="text" name="habilitacao" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Fundamento Legal</label>
                        <input type="text" name="fundamento_legal" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label>Publicação</label>
                        <input type="date" name="data_publicacao" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Encerramento</label>
                        <input type="date" name="data_encerramento" class="form-control">
                    </div>
                </div>
                <div class="text-right mt-4">
                    <a href="{{ route('licitacoes.index') }}" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@stop

