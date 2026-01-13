<?php

namespace App\Services;

use App\Models\Recebimento;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecebimentoService
{
    public function registrarRecebimento(array $data): Recebimento
    {
        return DB::transaction(function () use ($data) {
            $recebimento = Recebimento::create($data);

            // Se houver documento associado (ex: Nota Fiscal), realizar o atesto (validar)
            // Isso habilita o pagamento no módulo financeiro
            if (!empty($data['document_id'])) {
                $doc = Document::find($data['document_id']);
                if ($doc && $doc->status !== 'VALID') {
                    $doc->update(['status' => 'VALID']); 
                    Log::info("Documento ID {$doc->id} validado automaticamente via Recebimento ID {$recebimento->id}");
                }
            }

            Log::info("Recebimento registrado: ID {$recebimento->id} na unidade {$data['unidade_id']} por usuário {$data['recebido_por']}");
            return $recebimento;
        });
    }
}
