<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

abstract class BaseDataTableController extends Controller
{
    /**
     * Retorne a query base
     */
    abstract protected function query(): Builder;

    /**
     * Colunas permitidas (segurança)
     */
    protected function columns(): array
    {
        return [];
    }

    /**
     * Colunas com HTML
     */
    protected function rawColumns(): array
    {
        return ['acoes'];
    }

    /**
     * Render de ações (opcional)
     */
    protected function actions($model): ?string
    {
        return null;
    }

    /**
     * Endpoint padrão DataTables
     */
    public function data(Request $request)
    {
        $datatable = DataTables::of($this->query());

        // Limita colunas buscáveis/ordenáveis
        if ($cols = $this->columns()) {
            $datatable->filterColumn('*', function () {});
        }

        // Coluna ações (se existir)
        if (method_exists($this, 'actions')) {
            $datatable->addColumn('acoes', fn ($row) => $this->actions($row));
        }

        return $datatable
            ->rawColumns($this->rawColumns())
            ->make(true);
    }
}
