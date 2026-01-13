@extends('adminlte::page')

@section('title', 'Avarias')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Avarias (Notas Técnicas)</h1>
        <a href="{{ route('avarias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Avaria
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
                        <th>Equipamento</th>
                        <th>Patrimônio</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avarias as $avaria)
                        <tr>
                            <td>{{ $avaria->id }}</td>
                            <td>{{ $avaria->equipamento }}</td>
                            <td>{{ $avaria->patrimonio }}</td>
                            <td>{{ $avaria->prioridade }}</td>
                            <td>{{ $avaria->status }}</td>
                            <td>{{ $avaria->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="#" class="btn btn-xs btn-default text-primary mx-1" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma avaria registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $avarias->links() }}
            </div>
        </div>
    </div>
@stop
