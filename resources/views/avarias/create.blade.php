@extends('adminlte::page')

@section('title', 'Nova Avaria')

@section('content_header')
    <h1>Registrar Nova Avaria</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('avarias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="equipamento">Equipamento</label>
                            <input type="text" name="equipamento" class="form-control" required placeholder="Ex: Ar Condicionado Split">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="patrimonio">Patrimônio</label>
                            <input type="text" name="patrimonio" class="form-control" placeholder="Ex: 001234">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prioridade">Prioridade</label>
                            <select name="prioridade" class="form-control">
                                <option value="BAIXA">BAIXA</option>
                                <option value="MEDIA" selected>MEDIA</option>
                                <option value="ALTA">ALTA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao_problema">Descrição do Problema</label>
                    <textarea name="descricao_problema" class="form-control" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="unidade_id">Unidade</label>
                    <select name="unidade_id" class="form-control">
                        <option value="">Selecione a Unidade...</option>
                        @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="arquivo_documento">Foto/Laudo (Opcional)</label>
                    <input type="file" name="arquivo_documento" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="{{ route('avarias.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
