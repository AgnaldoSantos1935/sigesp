@extends('adminlte::page')

@section('title', 'Editar Item')

@section('content_header')
    <h1>Editar Item - Contrato {{ $instrumento->numero }}</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('instrumentos.items.update', [$instrumento->id, $item->id]) }}" method="POST">
        @csrf
        @method('PUT')
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
                    <input type="text" name="numero_item" class="form-control" value="{{ old('numero_item', $item->numero_item) }}">
                </div>
                <div class="col-md-8">
                    <label>Descrição</label>
                    <input type="text" name="descricao" class="form-control" required value="{{ old('descricao', $item->descricao) }}">
                </div>
                <div class="col-md-2">
                    <label>Unid. Medida</label>
                    <input type="text" name="unidade_medida" class="form-control" value="{{ old('unidade_medida', $item->unidade_medida) }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label>Quantidade Total</label>
                    <input type="number" step="0.0001" name="quantidade_total" class="form-control" required value="{{ old('quantidade_total', $item->quantidade_total) }}">
                </div>
                <div class="col-md-3">
                    <label>Valor Unitário</label>
                    <input type="number" step="0.01" name="valor_unitario" class="form-control" required value="{{ old('valor_unitario', $item->valor_unitario) }}">
                </div>
            </div>

            <hr>
            <h4>Distribuição por Unidade</h4>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Selecione as unidades e defina a quantidade destinada a cada uma.
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Unidades Escolares</h5>
                    <div class="form-group">
                        <label>Selecione as Escolas:</label>
                        <select id="select-escolas" name="unidades_escolares[]" class="form-control select2" multiple style="width: 100%;">
                            @foreach($escolas as $escola)
                                <option value="{{ $escola->id_escola }}" 
                                    {{ $item->unidadesEscolares->contains('id_escola', $escola->id_escola) ? 'selected' : '' }}>
                                    {{ $escola->nome_escola }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="container-qtd-escolas" class="mt-3">
                        @foreach($item->unidadesEscolares as $ue)
                            <div class="form-group row" id="row-escola-{{ $ue->id_escola }}">
                                <label class="col-sm-8 col-form-label">{{ $ue->nome_escola }}</label>
                                <div class="col-sm-4">
                                    <input type="number" step="0.0001" name="qtd_escolas[{{ $ue->id_escola }}]" 
                                           class="form-control form-control-sm" placeholder="Qtd" 
                                           value="{{ $ue->pivot->quantidade }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <h5>Unidades Administrativas</h5>
                    <div class="form-group">
                        <label>Selecione os Departamentos:</label>
                        <select id="select-adms" name="unidades_administrativas[]" class="form-control select2" multiple style="width: 100%;">
                            @foreach($adms as $adm)
                                <option value="{{ $adm->id }}"
                                    {{ $item->unidadesAdministrativas->contains('id', $adm->id) ? 'selected' : '' }}>
                                    {{ $adm->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="container-qtd-adms" class="mt-3">
                         @foreach($item->unidadesAdministrativas as $ua)
                            <div class="form-group row" id="row-adm-{{ $ua->id }}">
                                <label class="col-sm-8 col-form-label">{{ $ua->nome }}</label>
                                <div class="col-sm-4">
                                    <input type="number" step="0.0001" name="qtd_adms[{{ $ua->id }}]" 
                                           class="form-control form-control-sm" placeholder="Qtd" 
                                           value="{{ $ua->pivot->quantidade }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer text-right">
            <a href="{{ route('instrumentos.items.index', $instrumento->id) }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Handle Dynamic Inputs for Escolas
        $('#select-escolas').on('select2:select', function (e) {
            var data = e.params.data;
            var html = `
                <div class="form-group row" id="row-escola-${data.id}">
                    <label class="col-sm-8 col-form-label">${data.text}</label>
                    <div class="col-sm-4">
                        <input type="number" step="0.0001" name="qtd_escolas[${data.id}]" 
                               class="form-control form-control-sm" placeholder="Qtd">
                    </div>
                </div>
            `;
            $('#container-qtd-escolas').append(html);
        });

        $('#select-escolas').on('select2:unselect', function (e) {
            var data = e.params.data;
            $('#row-escola-' + data.id).remove();
        });

        // Handle Dynamic Inputs for Adms
        $('#select-adms').on('select2:select', function (e) {
            var data = e.params.data;
            var html = `
                <div class="form-group row" id="row-adm-${data.id}">
                    <label class="col-sm-8 col-form-label">${data.text}</label>
                    <div class="col-sm-4">
                        <input type="number" step="0.0001" name="qtd_adms[${data.id}]" 
                               class="form-control form-control-sm" placeholder="Qtd">
                    </div>
                </div>
            `;
            $('#container-qtd-adms').append(html);
        });

        $('#select-adms').on('select2:unselect', function (e) {
            var data = e.params.data;
            $('#row-adm-' + data.id).remove();
        });
    });
</script>
@stop
