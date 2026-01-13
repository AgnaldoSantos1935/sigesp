<?php

namespace App\Services;

use App\Models\Necessidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NecessidadeService
{
    public function solicitarNecessidade(array $data): Necessidade
    {
        return DB::transaction(function () use ($data) {
            // Define status inicial sempre como PENDENTE, independente do input
            $data['status'] = 'PENDENTE';
            
            $necessidade = Necessidade::create($data);
            Log::info("Necessidade solicitada: ID {$necessidade->id} categoria {$data['categoria']}");
            return $necessidade;
        });
    }

    public function analisarNecessidade(Necessidade $necessidade, string $status, ?string $motivo = null): Necessidade
    {
        $necessidade->update(['status' => $status]);
        Log::info("Necessidade ID {$necessidade->id} alterada para {$status}. Motivo: {$motivo}");
        return $necessidade;
    }
}
