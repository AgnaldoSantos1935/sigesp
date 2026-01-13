@extends('adminlte::page')

@section('title', 'Novo Empenho')

@section('content_header')
    <h1>Novo Empenho</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('empenhos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ano">Ano</label>
                            <input type="number" name="ano" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_emissao">Data Emissão</label>
                            <input type="date" name="data_emissao" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="valor">Valor (R$)</label>
                            <input type="number" step="0.01" name="valor" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value="ORDINARIO">ORDINARIO</option>
                                <option value="GLOBAL">GLOBAL</option>
                                <option value="ESTIMATIVO">ESTIMATIVO</option>
                            </select>
                        </div>
                    </div>
                    <!-- Placeholder for Contrato Item Select -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="contrato_item_id">Item de Contrato (Fonte)</label>
                            <select name="contrato_item_id" class="form-control">
                                <option value="">Selecione...</option>
                                @foreach($contratoItens as $item)
                                    <option value="{{ $item->id }}">Item #{{ $item->numero_item }} - {{ \Illuminate\Support\Str::limit($item->descricao, 50) }} (R$ {{ number_format($item->valor_total, 2, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição / Histórico</label>
                    <textarea name="descricao" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="arquivo_documento">Arquivo da Nota de Empenho (PDF/Img)</label>
                    <input type="file" name="arquivo_documento" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn btn-primary">Salvar Empenho</button>
                <a href="{{ route('empenhos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
