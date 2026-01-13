@extends('adminlte::page')

@section('title', 'Nova Designação')

@section('content_header')
    <h1>Nova Designação</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('designacoes.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Instrumento</label>
                        <select name="instrumento_juridico_id" class="form-control" required>
                            @foreach($instrumentos as $i)
                                <option value="{{ $i->id }}">{{ $i->numero }} - {{ $i->objeto }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Gestor</label>
                        <select name="gestor_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($pessoas as $p)
                                <option value="{{ $p->id }}">{{ $p->nome_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Fiscal Adm.</label>
                        <select name="fiscal_adm_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($pessoas as $p)
                                <option value="{{ $p->id }}">{{ $p->nome_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Fiscal Técnico</label>
                        <select name="fiscal_tecnico_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($pessoas as $p)
                                <option value="{{ $p->id }}">{{ $p->nome_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Suplente Adm.</label>
                        <select name="suplente_adm_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($pessoas as $p)
                                <option value="{{ $p->id }}">{{ $p->nome_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Cargo</label>
                        <select name="cargo_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($cargos as $c)
                                <option value="{{ $c->id }}">{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Portaria</label>
                        <input type="text" name="portaria" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Diário Oficial</label>
                        <input type="text" name="diario_oficial" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label>Publicação</label>
                        <input type="date" name="data_publicacao" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>A contar de</label>
                        <input type="date" name="a_contar" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Função</label>
                        <input type="text" name="funcao" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Descrição Função</label>
                        <input type="text" name="descricao_funcao" class="form-control">
                    </div>
                </div>
                <div class="text-right mt-4">
                    <a href="{{ route('instrumentos.index') }}" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@stop

