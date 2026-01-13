@extends('adminlte::page')

@section('title', 'Unidades Escolares')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Unidades Escolares</h1>
        <a href="{{ route('unidades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Unidade
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="unidades-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="50px">ID</th>
                        <th width="100px">INEP</th>
                        <th>Nome</th>
                        <th>Regional</th>
                        <th>Município</th>
                        <th width="100px">Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#unidades-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('unidades.data') }}",
                columns: [
                    { data: 'id_escola', name: 'id_escola' },
                    { data: 'codigo_inep', name: 'codigo_inep' },
                    { data: 'nome_escola', name: 'nome_escola' },
                    { data: 'regional.nome', name: 'regional.nome', defaultContent: 'N/A' },
                    { data: 'municipio', name: 'municipio' },
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        });
    </script>
@stop
