@extends('adminlte::page')

@section('title', 'Nova Pessoa Jurídica')

@section('content_header')
    <h1>Nova Pessoa Jurídica</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cadastro Completo</h3>
    </div>
    <form action="{{ route('pessoas_juridicas.store') }}" method="POST">
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
                <!-- DADOS DA EMPRESA -->
                <div class="col-12">
                    <fieldset class="border p-3 mb-3 rounded">
                        <legend class="w-auto h5">Dados da Empresa</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="razao_social">Razão Social *</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social" value="{{ old('razao_social') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome_fantasia">Nome Fantasia</label>
                                    <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cnpj">CNPJ *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cod_cnae">CNAE Principal</label>
                                    <input type="text" class="form-control" id="cod_cnae" name="cod_cnae" value="{{ old('cod_cnae') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cod_natjuridica">Cód. Natureza Jurídica</label>
                                    <input type="text" class="form-control" id="cod_natjuridica" name="cod_natjuridica" value="{{ old('cod_natjuridica') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_pessoa">Tipo Pessoa</label>
                                    <input type="text" class="form-control" id="tipo_pessoa" name="tipo_pessoa" value="{{ old('tipo_pessoa') }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="ramo_atividade">Ramo de Atividade</label>
                                    <input type="text" class="form-control" id="ramo_atividade" name="ramo_atividade" value="{{ old('ramo_atividade') }}">
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

                <!-- REPRESENTANTE -->
                <div class="col-12">
                    <fieldset class="border p-3 mb-3 rounded">
                        <legend class="w-auto h5">Representante</legend>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="representante_id">Pessoa Física (Representante)</label>
                                    <select class="form-control select2" id="representante_id" name="representante_id">
                                        <option value="">Selecione um representante...</option>
                                        @foreach($pessoasFisicas as $pf)
                                            <option value="{{ $pf->id }}" {{ old('representante_id') == $pf->id ? 'selected' : '' }}>
                                                {{ $pf->nome_completo }} (CPF: {{ $pf->cpf }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="representante_tipo">Tipo de Representação</label>
                                    <select class="form-control" id="representante_tipo" name="representante_tipo">
                                        <option value="LEGAL" {{ old('representante_tipo') == 'LEGAL' ? 'selected' : '' }}>Representante Legal</option>
                                        <option value="PROCURADOR" {{ old('representante_tipo') == 'PROCURADOR' ? 'selected' : '' }}>Procurador</option>
                                        <option value="TECNICO" {{ old('representante_tipo') == 'TECNICO' ? 'selected' : '' }}>Responsável Técnico</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="representante_inicio">Início Vigência</label>
                                    <input type="date" class="form-control" id="representante_inicio" name="representante_inicio" value="{{ old('representante_inicio', date('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="representante_fim">Fim Vigência</label>
                                    <input type="date" class="form-control" id="representante_fim" name="representante_fim" value="{{ old('representante_fim') }}">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('pessoas_juridicas.index') }}" class="btn btn-default">Voltar</a>
        </div>
    </form>
</div>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#cnpj').mask('00.000.000/0000-00');
            $('#cep').mask('00000-000');
            $('#telefone_fixo').mask('(00) 0000-0000');
            $('#celular_1').mask('(00) 00000-0000');
            $('#celular_2').mask('(00) 00000-0000');
        });
    </script>
@stop
