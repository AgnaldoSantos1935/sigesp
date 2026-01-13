<?php

namespace App\Services;

use App\Models\Medicao;
use App\Models\OrdemServico;
use App\Models\Atividade;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Exception;

class MedicaoService
{
    public function createMedicao(OrdemServico $os, array $data): Medicao
    {
        return DB::transaction(function () use ($os, $data) {
            // Validate period
            $inicio = $data['periodo_inicio'];
            $fim = $data['periodo_fim'];

            // Calculate value based on completed activities in the period
            // Or allow manual override if provided? 
            // The prompt says "Medição suporta UST / PF / Horas". 
            // The value comes from activities.
            
            $valorCalculado = $this->calculateValorPeriodo($os, $inicio, $fim);
            
            // If data contains 'valor_medido', validate it? 
            // Or just use calculated. Let's use calculated to be safe, or allow slight adjustments if needed.
            // For now, strict calculation.
            if ($valorCalculado <= 0) {
                throw new Exception("Não há atividades concluídas no período para medição.");
            }

            $medicao = new Medicao();
            $medicao->fill($data);
            $medicao->ordem_servico_id = $os->id;
            $medicao->valor_medido = $valorCalculado;
            $medicao->status = 'RASCUNHO';
            
            // Link Document if provided (Relatório de Medição)
            if (isset($data['document_id'])) {
                $medicao->document_id = $data['document_id'];
            }

            $medicao->save();

            return $medicao;
        });
    }

    private function calculateValorPeriodo(OrdemServico $os, $inicio, $fim): float
    {
        return $os->atividades()
            ->where('status', 'CONCLUIDA')
            ->whereBetween('updated_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59'])
            ->sum('valor_total');
    }
}
