<?php

namespace App\Services;

use App\Models\Avaria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AvariaService
{
    public function reportarAvaria(array $data): Avaria
    {
        return DB::transaction(function () use ($data) {
            $avaria = Avaria::create($data);
            Log::info("Avaria reportada: ID {$avaria->id} pelo usuário {$data['user_id']}");
            return $avaria;
        });
    }

    public function atualizarStatus(Avaria $avaria, string $status): Avaria
    {
        $oldStatus = $avaria->status;
        $avaria->update(['status' => $status]);
        Log::info("Status da Avaria ID {$avaria->id} alterado de {$oldStatus} para {$status}");
        return $avaria;
    }
}
