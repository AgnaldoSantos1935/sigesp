<div class="btn-group btn-group-sm">
    <a href="{{ route('instrumentos.items.index', $row->id) }}" class="btn btn-info" title="Gerenciar Itens"><i class="fas fa-list"></i> Itens</a>
    <a href="{{ route('termos_aditivos.create') }}" class="btn btn-secondary" title="Aditivo"><i class="fas fa-plus"></i> TA</a>
    <a href="{{ route('apostilamentos.create') }}" class="btn btn-secondary" title="Apostilamento"><i class="fas fa-edit"></i> AP</a>
    <a href="{{ route('designacoes.create') }}" class="btn btn-secondary" title="Designar"><i class="fas fa-user-tag"></i> Des</a>
</div>
