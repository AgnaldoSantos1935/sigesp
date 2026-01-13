@extends('adminlte::page')

@section('title', 'Detalhes da Unidade')

@section('content_header')
    <h1>{{ $unidade->nome_escola }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>INEP:</strong> {{ $unidade->codigo_inep }}</p>
            <p><strong>Município:</strong> {{ $unidade->municipio }}</p>
            <p><strong>Regional:</strong> {{ $unidade->regional->nome ?? 'N/A' }}</p>
            <p><strong>Endereço:</strong> {{ $unidade->endereco }}</p>
            <a href="{{ route('unidades.index') }}" class="btn btn-default">Voltar</a>
        </div>
    </div>
@stop
