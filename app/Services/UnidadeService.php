<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Unidade;
use App\Models\UnidadeVinculoAdministrativo;
use App\Models\Dre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use InvalidArgumentException;

class UnidadeService
{
    /**
     * Designa um novo vínculo administrativo (DRE ou Dirigente) para a unidade.
     * Encerra o vínculo anterior vigente e cria um novo.
     *
     * @param Unidade $unidade
     * @param Model $vinculado (Dre ou User/Pessoa)
     * @param Document $document Portaria de designação
     * @param string $tipoVinculo 'DRE' ou 'GESTOR'
     * @param Carbon|null $dataInicio Data de início do vínculo (default: agora)
     * @return UnidadeVinculoAdministrativo
     * @throws InvalidArgumentException
     */
    public function designarVinculo(
        Unidade $unidade,
        Model $vinculado,
        Document $document,
        string $tipoVinculo,
        ?Carbon $dataInicio = null
    ): UnidadeVinculoAdministrativo {
        $dataInicio = $dataInicio ?? now();

        // Validação básica de tipos
        if ($tipoVinculo === 'DRE' && !($vinculado instanceof Dre)) {
            throw new InvalidArgumentException("Para vínculo do tipo DRE, o vinculado deve ser uma instância de Dre.");
        }

        // Poderíamos validar GESTOR também, mas vamos deixar genérico (Model) para flexibilidade

        return DB::transaction(function () use ($unidade, $vinculado, $document, $tipoVinculo, $dataInicio) {
            // 1. Encerrar vínculo vigente
            $currentLink = $unidade->vinculos()
                ->where('tipo_vinculo', $tipoVinculo)
                ->whereNull('data_fim')
                ->first();

            if ($currentLink) {
                if ($currentLink->data_inicio->gt($dataInicio)) {
                     throw new InvalidArgumentException("A data de início do novo vínculo não pode ser anterior ao início do vínculo atual.");
                }

                $currentLink->update(['data_fim' => $dataInicio]);
            }

            // 2. Criar novo vínculo
            $novoVinculo = UnidadeVinculoAdministrativo::create([
                'unidade_id' => $unidade->id,
                'tipo_vinculo' => $tipoVinculo,
                'vinculado_type' => get_class($vinculado),
                'vinculado_id' => $vinculado->getKey(),
                'document_id' => $document->id,
                'data_inicio' => $dataInicio,
            ]);

            // 3. Atualizar cache na Unidade se for DRE
            if ($tipoVinculo === 'DRE' && $vinculado instanceof Dre) {
                $unidade->update(['dre_id' => $vinculado->id]);
            }

            return $novoVinculo;
        });
    }
}
