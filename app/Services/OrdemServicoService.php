<?php

namespace App\Services;

use App\Models\OrdemServico;
use App\Models\Atividade;
use App\Models\Complexidade;
use Illuminate\Support\Facades\DB;
use Exception;

class OrdemServicoService
{
    public function createOs(array $data): OrdemServico
    {
        return DB::transaction(function () use ($data) {
            return OrdemServico::create($data);
        });
    }

    public function addAtividade(OrdemServico $os, array $data): Atividade
    {
        return DB::transaction(function () use ($os, $data) {
            $complexidade = Complexidade::findOrFail($data['complexidade_id']);
            
            // Validate if complexidade belongs to the same contract as OS?
            // OS -> ContratoItem -> Contrato
            // Complexidade -> Contrato
            // Assuming strict validation is good practice
            $osContratoId = $os->contratoItem->contrato_id;
            if ($complexidade->contrato_id !== $osContratoId) {
                throw new Exception("Complexidade não pertence ao contrato da OS.");
            }

            $quantidade = $data['quantidade'];
            $valorUnitario = $complexidade->valor_unitario; // Base value
            $fator = $complexidade->fator;

            // Calculation: (Valor Unitário * Fator) * Quantidade? 
            // Or Valor Unitário * Quantidade * Fator? (Mathematically same)
            // Let's assume Valor Unitário is the base price per unit, and Factor adjusts it (e.g. complexity multiplier)
            $valorTotal = $quantidade * $valorUnitario * $fator;

            $atividade = new Atividade();
            $atividade->fill($data);
            $atividade->ordem_servico_id = $os->id;
            $atividade->valor_unitario = $valorUnitario * $fator; // Store the effective unit price
            $atividade->valor_total = $valorTotal;
            $atividade->status = 'PENDENTE';
            $atividade->save();

            return $atividade;
        });
    }

    public function completeAtividade(Atividade $atividade): void
    {
        $atividade->status = 'CONCLUIDA';
        $atividade->save();
    }
}
