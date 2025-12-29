<div class="row">
    <div class="col-md-4">

    <a href="{{ route('pessoas.edit', $pessoa->id) }}" class="btn btn-sm btn-primary" title="Editar">
        <i class="fa fa-lg fa-fw fa-pen"></i>
    </a>
    </div>
    <div class="col-md-4">
    <a href="{{ route('pessoas.show', $pessoa->id) }}" class="btn btn-sm btn-success" title="Visualizar">
        <i class="fa fa-lg fa-fw fa-eye"></i>
    </a>
    </div>
    <div class="col-md-4">
    <a href="deletePessoa({{ $pessoa->id }})" class="btn btn-sm btn-danger" title="Exportar">
        <i class="fa fa-lg fa-fw fa-download"></i>
    </a>
    </div>
</div>
</div>
