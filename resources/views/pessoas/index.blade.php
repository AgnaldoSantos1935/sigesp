@extends('adminlte::page')

@section('title', 'Pessoas')

@section('content_header')
    <h1>Pessoas Físicas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Pessoas</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" onclick="openPessoaCreateModal()">
                    <i class="fas fa-plus"></i> Cadastrar
                </button>

            </div>
        </div>
        <div class="card-body">
            <table id="pessoastable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome Completo</th>
                        <th>CPF</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i> Cadastro de Pessoa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" id="modal-create-body">
                <div class="text-center text-muted p-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-3">Carregando formulário...</p>
                </div>
            </div>

        </div>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@stop

@push('js')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/fiscalizer-datatables.js') }}"></script>
    <script>
        function openPessoaCreateModal() {

            const modal = $('#modal-create');
            const body = $('#modal-create-body');

            // loading
            body.html(`
        <div class="text-center text-muted p-5">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-3">Carregando formulário...</p>
        </div>
    `);

            modal.modal('show');

            $.ajax({
                url: "{{ route('pessoas.create') }}",
                method: 'GET',
                success: function(html) {
                    body.html(html);
                },
                error: function() {
                    body.html(`
                <div class="alert alert-danger">
                    Erro ao carregar o formulário.
                </div>
            `);
                }
            });
        }

        $(function() {
            FiscalizerDataTable('#pessoastable', {
                ajax: "{{ route('pessoas.data') }}",
                autoWidth: false,
                processing: false,
                serverSide: true,
                responsive: true,
                searchable: false,
                columnDefs: [{
                        width: '30px',
                        targets: 0
                    }, // ID
                    {
                        width: '250px',
                        targets: 1
                    }, // Nome
                    {
                        width: '100px',
                        targets: 2
                    }, // CPF
                    {
                        width: '40px',
                        targets: 3
                    }, // Ações
                    {
                        width: '70px',
                        targets: 4
                    }, // Ações
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nome_completo',
                        name: 'nome_completo'
                    },
                    {
                        data: 'cpf',
                        name: 'cpf'
                    },
                    {
                        data: 'ativo',
                        name: 'ativo',
                        render: function(data) {
                            return data ? '<span class="badge badge-success">Sim</span>' :
                                '<span class="badge badge-danger">Não</span>';
                        }
                    },
                    {
                        data: 'acoes',
                        name: 'acoes',
                        orderable: false,
                        searchable: false
                    }

                ]
            });
        });

let cepTimeout = null;

document.addEventListener('keyup', function (e) {
    if (e.target.id !== 'cep') return;

    clearTimeout(cepTimeout);

    const cep = e.target.value.replace(/\D/g, '');

    if (cep.length !== 8) return;

    cepTimeout = setTimeout(() => {
        consultarViaCep(cep);
    }, 400); // debounce
});

function consultarViaCep(cep) {

    preencherEnderecoLoading();

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(data => {

            if (data.erro) {
                toastr.error('CEP não encontrado');
                limparEndereco();
                return;
            }

            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('bairro').value     = data.bairro || '';
            document.getElementById('municipio').value  = data.localidade || '';
            document.getElementById('uf').value          = data.uf || '';
            document.getElementById('pais').value        = 'Brasil';

            toastr.success('Endereço preenchido automaticamente');

        })
        .catch(() => {
            toastr.error('Erro ao consultar o CEP');
            limparEndereco();
        });
}

function preencherEnderecoLoading() {
    ['logradouro', 'bairro', 'municipio', 'uf'].forEach(id => {
        document.getElementById(id).value = '...';
    });
}

function limparEndereco() {
    ['logradouro', 'bairro', 'municipio', 'uf'].forEach(id => {
        document.getElementById(id).value = '';
    });
}
</script>
<script>
/* JQUERY */
document.addEventListener('input', function (e) {
    if (e.target.id === 'cep') {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length > 5) {
            v = v.replace(/^(\d{5})(\d{1,3})$/, '$1-$2');
        }
        e.target.value = v;
    }
});
    </script>
@endpush
