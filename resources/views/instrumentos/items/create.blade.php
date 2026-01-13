@extends('adminlte::page')

@section('title', 'Novo Item')

@section('content_header')
    <h1>Novo Item - Contrato {{ $instrumento->numero }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('instrumentos.items.store', $instrumento->id) }}" method="POST">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-2">
                    <label>Número Item</label>
                    <input type="text" name="numero_item" class="form-control" value="{{ old('numero_item') }}">
                </div>
                <div class="col-md-8">
                    <label>Descrição</label>
                    <input type="text" name="descricao" class="form-control" required value="{{ old('descricao') }}">
                </div>
                <div class="col-md-2">
                    <label>Unid. Medida</label>
                    <input type="text" name="unidade_medida" class="form-control" value="{{ old('unidade_medida') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label>Quantidade Total</label>
                    <input type="number" step="0.0001" name="quantidade_total" class="form-control" required value="{{ old('quantidade_total') }}">
                </div>
                <div class="col-md-3">
                    <label>Valor Unitário</label>
                    <input type="number" step="0.01" name="valor_unitario" class="form-control" required value="{{ old('valor_unitario') }}">
                </div>
            </div>

            <hr>
            <h4>Unidades Contempladas</h4>
            <div class="row">
                <div class="col-md-6">
                    <label>Unidades Escolares</label>
                    <select name="unidades_escolares[]" class="form-control select2" multiple style="width: 100%;">
                        @foreach($escolas as $escola)
                            <option value="{{ $escola->id_escola }}">{{ $escola->nome_escola }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Unidades Administrativas</label>
                    <select name="unidades_administrativas[]" class="form-control select2" multiple style="width: 100%;">
                        @foreach($adms as $adm)
                            <option value="{{ $adm->id }}">{{ $adm->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('instrumentos.items.index', $instrumento->id) }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar Item</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@stop
