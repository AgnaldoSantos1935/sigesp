@extends('adminlte::page')

@section('title', 'Designações')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Designações</h1>
        <a href="{{ route('designacoes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Designação
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="designacoes-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Instrumento</th>
                        <th>Gestor</th>
                        <th>Fiscal Adm</th>
                        <th>Fiscal Tec</th>
                        <th>Portaria</th>
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
            $('#designacoes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('designacoes.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'instrumento.numero', name: 'instrumento.numero', defaultContent: 'N/A' },
                    { data: 'gestor.nome_completo', name: 'gestor.nome_completo', defaultContent: 'N/A' },
                    { data: 'fiscal_adm.nome_completo', name: 'fiscalAdm.nome_completo', defaultContent: 'N/A' },
                    { data: 'fiscal_tecnico.nome_completo', name: 'fiscalTecnico.nome_completo', defaultContent: 'N/A' },
                    { data: 'portaria', name: 'portaria' },
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json" }
            });
        });
    </script>
@stop
