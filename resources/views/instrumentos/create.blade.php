@extends('adminlte::page')

@section('title', $titulo ?? 'Novo Instrumento Jurídico')

@section('content_header')
    <h1>{{ $titulo ?? 'Novo Instrumento Jurídico' }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($tipo) && $tipo == 'contrato' ? route('contratos.store') : (isset($tipo) && $tipo == 'convenio' ? route('convenios.store') : route('instrumentos.store')) }}">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <label>Fornecedor</label>
                        <select name="pessoa_juridica_id" class="form-control" required>
                            @foreach($fornecedores as $f)
                                <option value="{{ $f->id }}">{{ $f->razao_social }} ({{ $f->cnpj }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Licitacao</label>
                        <select name="licitacao_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($licitacoes as $l)
                                <option value="{{ $l->id }}">{{ $l->numero_licitacao }} - {{ $l->modalidade }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Preposto</label>
                        <select name="preposto_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($prepostos as $p)
                                <option value="{{ $p->id }}">{{ $p->nome_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    @if(isset($tipo))
                        <input type="hidden" name="tipo" value="{{ $tipo }}">
                        @if($tipo == 'contrato')
                        <div class="col-md-3">
                            <label>Categoria do Objeto</label>
                            <select name="categoria" class="form-control">
                                <option value="">Selecione...</option>
                                <option value="Serviços">Prestação de Serviços</option>
                                <option value="Bens">Aquisição de Bens</option>
                            </select>
                        </div>
                        @endif
                    @else
                        <div class="col-md-2">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control" id="tipo_select" onchange="toggleCategoria(this.value)">
                                <option value="contrato">Contrato</option>
                                <option value="convenio">Convênio</option>
                                <option value="arp">Ata de Registro de Preços</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="categoria_div" style="display: block;">
                            <label>Categoria do Objeto</label>
                            <select name="categoria" class="form-control">
                                <option value="">Selecione...</option>
                                <option value="Serviços">Prestação de Serviços</option>
                                <option value="Bens">Aquisição de Bens</option>
                            </select>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <label>Número</label>
                        <input type="text" name="numero" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Processo Adm.</label>
                        <input type="text" name="processo_administrativo" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label>Objeto</label>
                        <input type="text" name="objeto" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label>Valor Global</label>
                        <input type="number" step="0.01" name="valor_global" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Vigência Início</label>
                        <input type="date" name="vigencia_inicio" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Vigência Fim</label>
                        <input type="date" name="vigencia_fim" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Assinatura</label>
                        <input type="date" name="data_assinatura" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label>Publicação</label>
                        <input type="date" name="data_publicacao" class="form-control">
                    </div>
                </div>
                <div class="text-right mt-4">
                    <a href="{{ isset($tipo) && $tipo == 'contrato' ? route('contratos.index') : (isset($tipo) && $tipo == 'convenio' ? route('convenios.index') : route('instrumentos.index')) }}" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    function toggleCategoria(val) {
        var div = document.getElementById('categoria_div');
        if(val == 'contrato') {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';
        }
    }

    // Initial check
    document.addEventListener('DOMContentLoaded', function() {
        var tipoSelect = document.getElementById('tipo_select');
        if(tipoSelect) {
            toggleCategoria(tipoSelect.value);
        }
    });
</script>
@stop
