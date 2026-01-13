@extends('adminlte::page')

@section('title', 'Itens do Contrato ' . $instrumento->numero)

@section('content_header')
    <h1>Itens do Contrato: {{ $instrumento->numero }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Itens</h3>
        <div class="card-tools">
            <a href="{{ route('instrumentos.items.create', $instrumento->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Novo Item
            </a>
            <a href="{{ route('instrumentos.index') }}" class="btn btn-sm btn-default">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Descrição</th>
                    <th>Unid.</th>
                    <th>Qtd. Total</th>
                    <th>Valor Unit.</th>
                    <th>Valor Total</th>
                    <th>Unidades Contempladas</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instrumento->items as $item)
                    <tr>
                        <td>{{ $item->numero_item }}</td>
                        <td>{{ $item->descricao }}</td>
                        <td>{{ $item->unidade_medida }}</td>
                        <td>{{ number_format($item->quantidade_total, 4, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                        <td>
                            @foreach($item->unidadesEscolares as $ue)
                                <span class="badge badge-info" title="Qtd: {{ $ue->pivot->quantidade }}">{{ $ue->nome_escola }} ({{ $ue->pivot->quantidade }})</span>
                            @endforeach
                            @foreach($item->unidadesAdministrativas as $ua)
                                <span class="badge badge-secondary" title="Qtd: {{ $ua->pivot->quantidade }}">{{ $ua->nome }} ({{ $ua->pivot->quantidade }})</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('instrumentos.items.edit', [$instrumento->id, $item->id]) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>
                             <form action="{{ route('instrumentos.items.destroy', [$instrumento->id, $item->id]) }}" method="POST" onsubmit="return confirm('Tem certeza?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Nenhum item cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
