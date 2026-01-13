@extends('adminlte::page')

@section('title', $titulo ?? 'Instrumentos Jurídicos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $titulo ?? 'Instrumentos Jurídicos' }}</h1>
        @if(isset($tipo) && $tipo == 'contrato')
            <a href="{{ route('contratos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Contrato
            </a>
        @elseif(isset($tipo) && $tipo == 'convenio')
            <a href="{{ route('convenios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Convênio
            </a>
        @else
            <a href="{{ route('instrumentos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Instrumento
            </a>
        @endif
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="instrumentos-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        @if(!isset($tipo))
                        <th>Tipo</th>
                        @endif
                        @if(isset($tipo) && $tipo == 'contrato')
                        <th>Categoria</th>
                        @endif
                        <th>Objeto</th>
                        <th>Vigência</th>
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
            $('#instrumentos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ isset($tipo) && $tipo == 'contrato' ? route('contratos.data') : (isset($tipo) && $tipo == 'convenio' ? route('convenios.data') : route('instrumentos.data')) }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'numero', name: 'numero' },
                    @if(!isset($tipo))
                    { data: 'tipo', name: 'tipo' },
                    @endif
                    @if(isset($tipo) && $tipo == 'contrato')
                    { data: 'categoria', name: 'categoria' },
                    @endif
                    { data: 'objeto', name: 'objeto' },
                    { data: 'vigencia_fim', name: 'vigencia_fim' },
                    { data: 'acoes', name: 'acoes', orderable: false, searchable: false }
                ],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json" }
            });
        });
    </script>
@stop

