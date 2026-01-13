<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\InstrumentoJuridico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ContratoService
{
    public function create(array $data): Contrato
    {
        return DB::transaction(function () use ($data) {
            // Validar Instrumento Jurídico
            $instrumento = InstrumentoJuridico::findOrFail($data['instrumento_juridico_id']);

            // Validar Tenant
            $tenantId = currentTenant()->id ?? Auth::user()->tenant_id;
            if ($instrumento->tenant_id !== $tenantId) {
                throw new InvalidArgumentException('Instrumento Jurídico pertence a outro tenant.');
            }

            // Validar Tipo de Contrato
            $tiposValidos = [
                Contrato::TIPO_AQUISICAO,
                Contrato::TIPO_SERVICO,
                Contrato::TIPO_INTERNET,
                Contrato::TIPO_FABRICA_SOFTWARE
            ];

            if (!in_array($data['tipo_contrato'], $tiposValidos)) {
                throw new InvalidArgumentException('Tipo de contrato inválido.');
            }

            $contrato = Contrato::create([
                'tenant_id' => $tenantId,
                'instrumento_juridico_id' => $data['instrumento_juridico_id'],
                'numero' => $data['numero'],
                'ano' => $data['ano'],
                'objeto' => $data['objeto'],
                'fornecedor_nome' => $data['fornecedor_nome'],
                'fornecedor_cnpj' => $data['fornecedor_cnpj'],
                'tipo_contrato' => $data['tipo_contrato'],
                'status' => Contrato::STATUS_RASCUNHO, // Padrão inicial
            ]);

            return $contrato;
        });
    }

    public function updateStatus(Contrato $contrato, string $status): Contrato
    {
        $statusValidos = [
            Contrato::STATUS_RASCUNHO,
            Contrato::STATUS_ATIVO,
            Contrato::STATUS_ENCERRADO
        ];

        if (!in_array($status, $statusValidos)) {
            throw new InvalidArgumentException('Status inválido.');
        }

        $contrato->update(['status' => $status]);

        return $contrato;
    }
}
