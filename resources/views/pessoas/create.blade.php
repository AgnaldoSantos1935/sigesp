<form method="POST" action="{{ route('pessoas.store') }}">
    @csrf
    <div class="container-fluid">

        <div class="card card-outline card-primary">

            {{-- HEADER --}}
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-id-card"></i> Dados da Pessoa
                </h3>
            </div>

            <div class="card-body">

                {{-- TABS --}}
                <ul class="nav nav-tabs" id="pessoaTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#dados">Dados Pessoais</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#usuario">Usuário</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contato">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#endereco">Endereço</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#vinculo">Vínculo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#designacao">Designação</a>
                    </li>
                </ul>

                <div class="tab-content pt-4">

                    {{-- 1️⃣ DADOS PESSOAIS --}}
                    <div class="tab-pane fade show active" id="dados">

                        <div class="row">
                            <div class="col-md-6">
                                <label>Nome Completo</label>
                                <input type="text" name="nome_completo" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>CPF</label>
                                <input type="text" name="cpf" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Data de Nascimento</label>
                                <input type="date" name="data_nascimento" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Gênero</label>
                                <select name="genero" class="form-control">
                                    <option value="">Selecione</option>
                                    <option>Masculino</option>
                                    <option>Feminino</option>
                                    <option>Outro</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Tipo Sanguíneo</label>
                                <input type="text" name="tipo_sanguineo" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Ativo</label>
                                <select name="ativo" class="form-control">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 2️⃣ USUÁRIO --}}
                    <div class="tab-pane fade" id="usuario">

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Criação opcional de usuário para acesso ao sistema.
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Login</label>
                                <input type="text" name="user[login]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>E-mail</label>
                                <input type="email" name="user[email]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Senha</label>
                                <input type="password" name="user[password]" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- 3️⃣ CONTATO --}}
                    <div class="tab-pane fade" id="contato">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Telefone</label>
                                <input type="text" name="contato[telefone]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Celular</label>
                                <input type="text" name="contato[celular]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Email Secundário</label>
                                <input type="email" name="contato[email]" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- 4️⃣ ENDEREÇO --}}
                    <div class="tab-pane fade" id="endereco">
                        <div class="row">
                            <div class="col-md-3">
                                <label>CEP</label>

                                <input type="text" name="endereco[cep]" id="cep" class="form-control"
                                    placeholder="00000-000" maxlength="9" autocomplete="off" onblur="buscarCep(this.value)">


                            </div>

                            <div class="col-md-6">
                                <label>Logradouro</label>
                                <input type="text" name="endereco[logradouro]" id="logradouro"
                                    class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Número</label>
                                <input type="text" name="endereco[numero]" id="numero" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Bairro</label>
                                <input type="text" name="endereco[bairro]" id="bairro" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Município</label>
                                <input type="text" name="endereco[municipio]" id="municipio"
                                    class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label>UF</label>
                                <input type="text" name="endereco[uf]" id="uf" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label>País</label>
                                <input type="text" name="endereco[pais]" id="pais" class="form-control"
                                    value="Brasil">
                            </div>
                        </div>
                    </div>

                    {{-- 5️⃣ VÍNCULO --}}
                    <div class="tab-pane fade" id="vinculo">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tipo de Vínculo</label>
                                <input type="text" name="vinculo[tipo]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Matrícula</label>
                                <input type="text" name="vinculo[matricula]" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Data de Ingresso</label>
                                <input type="date" name="vinculo[data_ingresso]" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- 6️⃣ DESIGNAÇÃO --}}
                    <div class="tab-pane fade" id="designacao">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Papel</label>
                                <select name="designacao[papel]" class="form-control">
                                    <option>Gestor</option>
                                    <option>Fiscal Administrativo</option>
                                    <option>Fiscal Técnico</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Instrumento Jurídico</label>
                                <select name="designacao[instrumento_id]" class="form-control">
                                    {{-- carregar contratos --}}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>

</form>
