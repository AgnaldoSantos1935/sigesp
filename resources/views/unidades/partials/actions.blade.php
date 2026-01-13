<div class="btn-group btn-group-sm">
    <a href="{{ route('unidades.edit', $row->id_escola) }}" class="btn btn-primary" title="Editar">
        <i class="fas fa-pencil-alt"></i>
    </a>
    <a href="{{ route('unidades.show', $row->id_escola) }}" class="btn btn-info" title="Visualizar">
        <i class="fas fa-eye"></i>
    </a>
    <form action="{{ route('unidades.destroy', $row->id_escola) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" title="Excluir">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
