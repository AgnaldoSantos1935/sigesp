@extends('adminlte::page')

@section('title', 'Licitações')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Licitações</h1>
        <a href="{{ route('licitacoes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Licitação
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="licitacoes-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>Modalidade</th>
                        <th>Objeto</th>
                        <th>Ações</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#licitacoes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('licitacoes.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'numero_licitacao', name: 'numero_licitacao' },
                    { data: 'modalidade', name: 'modalidade' },
                    { data: 'objeto', name: 'objeto' },
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json" }
            });
        });
    </script>
@stop

