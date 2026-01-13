@extends('adminlte::page')

@section('title', 'Novo Apostilamento')

@section('content_header')
    <h1>Novo Apostilamento</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('apostilamentos.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Instrumento</label>
                        <select name="instrumento_juridico_id" class="form-control" required>
                            @foreach($instrumentos as $i)
                                <option value="{{ $i->id }}">{{ $i->numero }} - {{ $i->objeto }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Número</label>
                        <input type="text" name="numero" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Processo</label>
                        <input type="text" name="processo" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <label>Objeto</label>
                        <input type="text" name="objeto" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Publicação</label>
                        <input type="date" name="data_publicacao" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Assinatura</label>
                        <input type="date" name="data_assinatura" class="form-control">
                    </div>
                </div>
                <div class="text-right mt-4">
                    <a href="{{ route('instrumentos.index') }}" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@stop

