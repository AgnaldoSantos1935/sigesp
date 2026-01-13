@extends('adminlte::page')

@section('title', 'Empenhos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Empenhos</h1>
        <a href="{{ route('empenhos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Empenho
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>Ano</th>
                        <th>Data Emissão</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empenhos as $empenho)
                        <tr>
                            <td>{{ $empenho->id }}</td>
                            <td>{{ $empenho->numero }}</td>
                            <td>{{ $empenho->ano }}</td>
                            <td>{{ \Carbon\Carbon::parse($empenho->data_emissao)->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($empenho->valor, 2, ',', '.') }}</td>
                            <td>{{ $empenho->tipo }}</td>
                            <td><span class="badge badge-{{ $empenho->status == 'ATIVO' ? 'success' : 'danger' }}">{{ $empenho->status }}</span></td>
                            <td>
                                <a href="#" class="btn btn-xs btn-default text-primary mx-1" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Nenhum empenho encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $empenhos->links() }}
            </div>
        </div>
    </div>
@stop
