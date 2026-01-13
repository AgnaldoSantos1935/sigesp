<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Empenho;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagamentoService
{
    protected $empenhoService;

    public function __construct(EmpenhoService $empenhoService)
    {
        $this->empenhoService = $empenhoService;
    }

    /**
     * Registra um novo pagamento validando saldo, status e documentos.
     *
     * @param array $data
     * @return Pagamento
     * @throws ValidationException
     */
    public function registrarPagamento(array $data): Pagamento
    {
        return DB::transaction(function () use ($data) {
            // 1. Recuperar Empenho
            $empenho = Empenho::findOrFail($data['empenho_id']);

            // 2. Validar Status do Empenho
            if ($empenho->status !== Empenho::STATUS_ATIVO) {
                throw ValidationException::withMessages([
                    'empenho_id' => "O empenho informado não está ATIVO. Status atual: {$empenho->status}"
                ]);
            }

            // 3. Validar Documentos (OB e NF/Ateste)
            $this->validarDocumentos($data);

            // 4. Validar Saldo Disponível no Empenho
            $saldoEmpenho = $this->empenhoService->calcularSaldoDisponivelEmpenho($empenho);
            
            if ($data['valor'] > $saldoEmpenho) {
                throw ValidationException::withMessages([
                    'valor' => "Valor do pagamento (R$ " . number_format($data['valor'], 2, ',', '.') . ") excede o saldo disponível no empenho (R$ " . number_format($saldoEmpenho, 2, ',', '.') . ")."
                ]);
            }

            // 5. Registrar Pagamento
            // tenant_id é injetado automaticamente pelo BelongsToTenant trait
            $pagamento = Pagamento::create([
                'empenho_id' => $data['empenho_id'],
                'numero_ordem_bancaria' => $data['numero_ordem_bancaria'] ?? null,
                'data_pagamento' => $data['data_pagamento'],
                'valor' => $data['valor'],
                'status' => Pagamento::STATUS_PAGO,
                'document_id' => $data['document_id'], // Ordem Bancária
                'nota_fiscal_id' => $data['nota_fiscal_id'], // NF/Ateste
                'observacao' => $data['observacao'] ?? null,
            ]);

            // 6. Verificar se deve encerrar o empenho (se saldo zerou)
            $this->empenhoService->checkAndCloseEmpenho($empenho);

            return $pagamento;
        });
    }

    /**
     * Valida a existência e validade dos documentos vinculados.
     */
    protected function validarDocumentos(array $data): void
    {
        // Validar Ordem Bancária (document_id)
        $ob = Document::find($data['document_id']);
        if (!$ob || $ob->status !== Document::STATUS_VALID) {
            throw ValidationException::withMessages([
                'document_id' => 'O documento de Ordem Bancária não é válido ou não existe.'
            ]);
        }

        // Validar Nota Fiscal / Ateste (nota_fiscal_id)
        $nf = Document::find($data['nota_fiscal_id']);
        if (!$nf || $nf->status !== Document::STATUS_VALID) {
            throw ValidationException::withMessages([
                'nota_fiscal_id' => 'O documento Fiscal/Ateste não é válido ou não existe.'
            ]);
        }
    }

    /**
     * Estorna um pagamento.
     */
    public function estornarPagamento(Pagamento $pagamento, string $motivo): Pagamento
    {
        return DB::transaction(function () use ($pagamento, $motivo) {
            if ($pagamento->status !== Pagamento::STATUS_PAGO) {
                throw ValidationException::withMessages([
                    'status' => "Apenas pagamentos com status PAGO podem ser estornados."
                ]);
            }

            $pagamento->update([
                'status' => Pagamento::STATUS_ESTORNADO,
                'observacao' => $pagamento->observacao . " | Estornado: " . $motivo
            ]);

            return $pagamento;
        });
    }
}
