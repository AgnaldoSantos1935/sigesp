<?php

namespace App\Services;

use App\Models\Demanda;
use App\Models\OrdemServico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class DemandaService
{
    public function createDemanda(array $data, Model $demandante): Demanda
    {
        return DB::transaction(function () use ($data, $demandante) {
            $demanda = new Demanda();
            $demanda->fill($data);
            $demanda->demandante()->associate($demandante);
            $demanda->save();

            return $demanda;
        });
    }

    public function convertToOs(Demanda $demanda, int $contratoItemId, array $osData): OrdemServico
    {
        return DB::transaction(function () use ($demanda, $contratoItemId, $osData) {
            if ($demanda->status === 'CONVERTIDA_OS') {
                throw new Exception("Demanda já convertida em OS.");
            }

            $osService = app(OrdemServicoService::class);

            // Merge demanda data into OS data if needed, or link
            $osData['demanda_id'] = $demanda->id;
            $osData['contrato_item_id'] = $contratoItemId;

            $os = $osService->createOs($osData);

            $demanda->status = 'CONVERTIDA_OS';
            $demanda->save();

            return $os;
        });
    }
}
