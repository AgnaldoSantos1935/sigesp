@extends('adminlte::page')

@section('title', 'Apostilamentos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Apostilamentos</h1>
        <a href="{{ route('apostilamentos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Apostilamento
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="apostilamentos-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Instrumento</th>
                        <th>Número</th>
                        <th>Objeto</th>
                        <th>Data Publicação</th>
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
            $('#apostilamentos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('apostilamentos.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'instrumento.numero', name: 'instrumento.numero', defaultContent: 'N/A' },
                    { data: 'numero', name: 'numero' },
                    { data: 'objeto', name: 'objeto' },
                    { data: 'data_publicacao', name: 'data_publicacao' },
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json" }
            });
        });
    </script>
@stop
