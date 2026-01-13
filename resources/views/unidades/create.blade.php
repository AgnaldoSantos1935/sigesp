@extends('adminlte::page')

@section('title', 'Nova Unidade Escolar')

@section('content_header')
    <h1>Nova Unidade Escolar</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cadastro Completo</h3>
    </div>
    <form action="{{ route('unidades.store') }}" method="POST">
        @csrf
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!-- DADOS DA UNIDADE -->
                <div class="col-12">
                    <fieldset class="border p-3 mb-3 rounded">
                        <legend class="w-auto h5">Dados da Unidade</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">Nome da Escola *</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inep">Código INEP</label>
                                    <input type="text" class="form-control" id="inep" name="inep" value="{{ old('inep') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="regional_id">Regional</label>
                                    <select class="form-control select2" id="regional_id" name="regional_id">
                                        <option value="">Selecione...</option>
                                        @foreach($regionais as $regional)
                                            <option value="{{ $regional->id }}" {{ old('regional_id') == $regional->id ? 'selected' : '' }}>
                                                {{ $regional->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- ENDEREÇO -->
                <div class="col-12">
                    <fieldset class="border p-3 mb-3 rounded">
                        <legend class="w-auto h5">Endereço</legend>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cep">CEP *</label>
                                    <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep') }}" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="logradouro">Logradouro *</label>
                                    <input type="text" class="form-control" id="logradouro" name="logradouro" value="{{ old('logradouro') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="numero">Número</label>
                                    <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bairro">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="municipio">Município *</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio" value="{{ old('municipio') }}" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="uf">UF *</label>
                                    <input type="text" class="form-control" id="uf" name="uf" value="{{ old('uf') }}" maxlength="2" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="complemento">Complemento</label>
                                    <input type="text" class="form-control" id="complemento" name="complemento" value="{{ old('complemento') }}">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- CONTATO -->
                <div class="col-12">
                    <fieldset class="border p-3 mb-3 rounded">
                        <legend class="w-auto h5">Contato</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_1">Email Principal</label>
                                    <input type="email" class="form-control" id="email_1" name="email_1" value="{{ old('email_1') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_2">Email Secundário</label>
                                    <input type="email" class="form-control" id="email_2" name="email_2" value="{{ old('email_2') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefone_fixo">Telefone Fixo</label>
                                    <input type="text" class="form-control" id="telefone_fixo" name="telefone_fixo" value="{{ old('telefone_fixo') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="celular_1">Celular Principal</label>
                                    <input type="text" class="form-control" id="celular_1" name="celular_1" value="{{ old('celular_1') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="celular_2">Celular Secundário</label>
                                    <input type="text" class="form-control" id="celular_2" name="celular_2" value="{{ old('celular_2') }}">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- CHEFIA/GESTOR -->
                <div class="col-12">
                    <fieldset class="border p-3 mb-3 rounded">
                        <legend class="w-auto h5">Gestor / Chefe Escolar</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gestor_id">Selecione o Gestor (Pessoa Física)</label>
                                    <select class="form-control select2" id="gestor_id" name="gestor_id">
                                        <option value="">Selecione...</option>
                                        @foreach($pessoasFisicas as $pf)
                                            <option value="{{ $pf->id }}" {{ old('gestor_id') == $pf->id ? 'selected' : '' }}>
                                                {{ $pf->nome_completo }} (CPF: {{ $pf->cpf }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">A pessoa selecionada será cadastrada automaticamente como Chefia Escolar.</small>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('unidades.index') }}" class="btn btn-default float-right">Cancelar</a>
        </div>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap'
            });
        });
    </script>
@stop
