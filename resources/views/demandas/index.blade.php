@extends('adminlte::page')

@section('title', 'Demandas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Demandas de Software</h1>
        <a href="{{ route('demandas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Demanda
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
                        <th>Título</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Data Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandas as $demanda)
                        <tr>
                            <td>{{ $demanda->id }}</td>
                            <td>{{ $demanda->titulo }}</td>
                            <td>{{ $demanda->prioridade }}</td>
                            <td>
                                <span class="badge badge-{{ $demanda->status == 'APROVADA' ? 'success' : ($demanda->status == 'REJEITADA' ? 'danger' : 'warning') }}">
                                    {{ $demanda->status }}
                                </span>
                            </td>
                            <td>{{ $demanda->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-xs btn-default text-primary mx-1" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma demanda registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $demandas->links() }}
            </div>
        </div>
    </div>
@stop
