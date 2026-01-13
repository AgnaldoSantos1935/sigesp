@extends('adminlte::page')

@section('title', 'Nova Demanda')

@section('content_header')
    <h1>Nova Demanda de Software</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('demandas.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="titulo">Título da Demanda</label>
                            <input type="text" name="titulo" class="form-control" required placeholder="Ex: Criação de Relatório Financeiro">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prioridade">Prioridade</label>
                            <select name="prioridade" class="form-control">
                                <option value="BAIXA">BAIXA</option>
                                <option value="MEDIA" selected>MEDIA</option>
                                <option value="ALTA">ALTA</option>
                                <option value="CRITICA">CRÍTICA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição Detalhada</label>
                    <textarea name="descricao" class="form-control" rows="6" required placeholder="Descreva os requisitos, regras de negócio e objetivos..."></textarea>
                </div>

                <div class="form-group">
                    <label for="arquivo_documento">Anexo (Especificação/Mockup)</label>
                    <input type="file" name="arquivo_documento" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                </div>

                <button type="submit" class="btn btn-primary">Enviar Demanda</button>
                <a href="{{ route('demandas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
