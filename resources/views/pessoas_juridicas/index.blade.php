@extends('adminlte::page')

@section('title', 'Pessoas Jurídicas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Pessoas Jurídicas</h1>
        <a href="{{ route('pessoas_juridicas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Cadastro
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="pessoas-juridicas-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razão Social</th>
                        <th>CNPJ</th>
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
            $('#pessoas-juridicas-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pessoas_juridicas.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'razao_social', name: 'razao_social' },
                    { data: 'cnpj', name: 'cnpj' },
                    { data: 'endereco.municipio', name: 'endereco.municipio', defaultContent: '' },
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        });
    </script>
@stop
