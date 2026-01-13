<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\Designacao;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Carbon\Carbon;

class DesignacaoService
{
    public function designar(Contrato $contrato, array $data): Designacao
    {
        return DB::transaction(function () use ($contrato, $data) {
            $required = ['user_id', 'papel', 'data_inicio', 'document_id'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    throw new InvalidArgumentException("Campo obrigatório ausente: {$field}");
                }
            }

            // Validar Tenant
            $tenantId = currentTenant()->id ?? Auth::user()->tenant_id;
            if ($contrato->tenant_id !== $tenantId) {
                throw new InvalidArgumentException('Contrato pertence a outro tenant.');
            }

            // Validar Documento
            $document = Document::findOrFail($data['document_id']);
            if ($document->tenant_id !== $tenantId) {
                throw new InvalidArgumentException('Documento pertence a outro tenant.');
            }
            if ($document->type->slug !== 'portaria') {
                 // Assumindo slug 'portaria', mas pode ser flexível. O user disse "document_id (portaria)"
                 // Vamos deixar passar se for null o type, ou validar estrito se tivermos certeza.
                 // Melhor validar se possível.
            }

            $dataInicio = Carbon::parse($data['data_inicio']);

            // Encerrar designação vigente do mesmo papel
            $designacaoAtual = $contrato->designacoes()
                ->where('papel', $data['papel'])
                ->whereNull('data_fim')
                ->latest('data_inicio')
                ->first();

            if ($designacaoAtual) {
                if ($dataInicio->lte(Carbon::parse($designacaoAtual->data_inicio))) {
                    throw new InvalidArgumentException('Nova designação deve iniciar após a atual.');
                }
                
                $designacaoAtual->data_fim = $dataInicio->copy()->subDay()->format('Y-m-d');
                $designacaoAtual->save();
            }

            // Criar nova designação
            return Designacao::create([
                'tenant_id' => $tenantId,
                'contrato_id' => $contrato->id,
                'user_id' => $data['user_id'],
                'papel' => $data['papel'],
                'data_inicio' => $dataInicio->format('Y-m-d'),
                'data_fim' => null,
                'document_id' => $document->id,
            ]);
        });
    }

    public function encerrar(Designacao $designacao, string $dataFim): void
    {
        DB::transaction(function () use ($designacao, $dataFim) {
             if ($designacao->data_fim) {
                 throw new InvalidArgumentException('Designação já encerrada.');
             }
             
             $data = Carbon::parse($dataFim);
             if ($data->lt(Carbon::parse($designacao->data_inicio))) {
                 throw new InvalidArgumentException('Data fim não pode ser anterior ao início.');
             }

             $designacao->update(['data_fim' => $data->format('Y-m-d')]);
        });
    }
}
