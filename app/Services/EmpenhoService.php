<?php

namespace App\Services;

use App\Models\Empenho;
use App\Models\ContratoItem;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmpenhoService
{
    /**
     * Cria um novo empenho validando saldo e duplicidade.
     *
     * @param array $data
     * @return Empenho
     * @throws ValidationException
     */
    public function createEmpenho(array $data): Empenho
    {
        return DB::transaction(function () use ($data) {
            // 1. Validar Contrato Item (Existência e Pertencimento ao Tenant é garantido pelo Scope)
            $item = ContratoItem::findOrFail($data['contrato_item_id']);

            // 2. Validar Duplicidade (Número/Ano) no Tenant
            // O TenantScope já filtra por tenant, mas para unique validation precisamos garantir
            // que estamos verificando dentro do escopo do tenant atual.
            // O trait BelongsToTenant deve injetar o scope, mas explicitamos para clareza.
            $exists = Empenho::where('numero', $data['numero'])
                ->where('ano', $data['ano'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'numero' => "Já existe um empenho com o número {$data['numero']}/{$data['ano']} para este órgão."
                ]);
            }

            // 3. Validar Saldo Disponível no Item
            $saldoItem = $this->calcularSaldoDisponivelItem($item);

            if ($data['valor'] > $saldoItem) {
                throw ValidationException::withMessages([
                    'valor' => "Valor do empenho (R$ " . number_format($data['valor'], 2, ',', '.') . ") excede o saldo disponível no item (R$ " . number_format($saldoItem, 2, ',', '.') . ")."
                ]);
            }

            // 4. Validar Documento (Nota de Empenho)
            $document = Document::findOrFail($data['document_id']);
            if ($document->status !== Document::STATUS_VALID) {
                throw ValidationException::withMessages([
                    'document_id' => 'O documento informado (Nota de Empenho) não está válido.'
                ]);
            }

            // 5. Criar Empenho
            // tenant_id é injetado automaticamente pelo BelongsToTenant trait
            $empenho = Empenho::create([
                'contrato_item_id' => $data['contrato_item_id'],
                'numero' => $data['numero'],
                'ano' => $data['ano'],
                'data_emissao' => $data['data_emissao'],
                'valor' => $data['valor'],
                'descricao' => $data['descricao'] ?? null,
                'tipo' => $data['tipo'] ?? Empenho::TIPO_ORDINARIO,
                'status' => Empenho::STATUS_ATIVO,
                'document_id' => $data['document_id'],
            ]);

            return $empenho;
        });
    }

    /**
     * Calcula o saldo disponível para empenho em um item de contrato.
     * Saldo = Valor Total do Item - Soma(Empenhos Ativos)
     *
     * @param ContratoItem $item
     * @return float
     */
    public function calcularSaldoDisponivelItem(ContratoItem $item): float
    {
        $empenhado = $item->empenhos() // Assumindo relacionamento em ContratoItem
            ->where('status', Empenho::STATUS_ATIVO)
            ->sum('valor');

        return round($item->valor_total - $empenhado, 2);
    }

    /**
     * Calcula o saldo disponível de um Empenho.
     * Saldo = Valor Empenhado - Soma(Pagamentos Pagos)
     *
     * @param Empenho $empenho
     * @return float
     */
    public function calcularSaldoDisponivelEmpenho(Empenho $empenho): float
    {
        // Usa o accessor getValorPagoAttribute se disponível, ou soma direta
        $pago = $empenho->pagamentos()
            ->where('status', 'PAGO')
            ->sum('valor');

        return round($empenho->valor - $pago, 2);
    }

    /**
     * Tenta encerrar o empenho se o saldo estiver zerado.
     */
    public function checkAndCloseEmpenho(Empenho $empenho): void
    {
        $saldo = $this->calcularSaldoDisponivelEmpenho($empenho);

        // Se saldo zerado (ou muito próximo de zero devido a float point), encerra.
        if ($saldo <= 0.001) {
            $empenho->update(['status' => Empenho::STATUS_ENCERRADO]);
        }
    }
}
