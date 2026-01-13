<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Dre;
use App\Models\Unidade;
use App\Models\UnidadeVinculoAdministrativo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class UnidadeVinculoAdministrativoService
{
    public function changeVinculo(Unidade $unidade, array $dados): UnidadeVinculoAdministrativo
    {
        $required = ['dre_id', 'dirigente_nome', 'dirigente_cargo', 'document_id', 'data_inicio'];
        foreach ($required as $key) {
            if (!array_key_exists($key, $dados) || $dados[$key] === null || $dados[$key] === '') {
                throw new InvalidArgumentException("Campo obrigatório ausente: {$key}");
            }
        }

        $dataInicio = Carbon::parse($dados['data_inicio'])->startOfDay();
        if ($dataInicio->isPast()) {
            throw new InvalidArgumentException('Data de início não pode ser retroativa');
        }

        $dre = Dre::query()->whereKey($dados['dre_id'])->first();
        if (!$dre) {
            throw new InvalidArgumentException('DRE não encontrada');
        }

        $document = Document::query()->whereKey($dados['document_id'])->first();
        if (!$document) {
            throw new InvalidArgumentException('Documento (portaria) não encontrado');
        }

        if ($document->status !== Document::STATUS_VALID) {
            throw new InvalidArgumentException('Documento não está com status válido');
        }

        $type = $document->type;
        if (!$type || $type->slug !== 'portaria') {
            throw new InvalidArgumentException('Documento informado não é uma Portaria');
        }
        if (!$unidade->tenant_id || !$dre->tenant_id || !$document->tenant_id) {
            throw new RuntimeException('Tenant não definido em um dos registros envolvidos');
        }
        if ($unidade->tenant_id !== $dre->tenant_id || $unidade->tenant_id !== $document->tenant_id) {
            throw new InvalidArgumentException('Registros pertencem a tenants diferentes');
        }

        $currentUserId = Auth::id();
        if (!$currentUserId) {
            throw new RuntimeException('Usuário não autenticado');
        }

        $current = $unidade->vinculos()
            ->whereNull('data_fim')
            ->latest('data_inicio')
            ->first();

        if ($current && $dataInicio->lte(Carbon::parse($current->data_inicio))) {
            throw new InvalidArgumentException('Data de início não pode sobrepor ou ser anterior ao vínculo vigente');
        }

        $overlapExists = $unidade->vinculos()
            ->where(function ($q) use ($dataInicio) {
                $q->whereNull('data_fim')
                  ->orWhere('data_fim', '>=', $dataInicio->format('Y-m-d'));
            })
            ->where('data_inicio', '<=', $dataInicio->format('Y-m-d'))
            ->exists();

        if ($overlapExists && !$current) {
            throw new InvalidArgumentException('Período informado sobrepõe vínculo existente');
        }

        return DB::transaction(function () use ($unidade, $dre, $document, $dados, $dataInicio, $currentUserId, $current) {
            if ($current) {
                $current->data_fim = $dataInicio->copy()->subDay()->format('Y-m-d');
                if (Carbon::parse($current->data_fim)->lt(Carbon::parse($current->data_inicio))) {
                    throw new InvalidArgumentException('Data fim do vínculo vigente ficaria anterior ao início');
                }
                $current->save();
            }

            $unidade->dre_id = $dre->id;
            $unidade->save();

            $novo = new UnidadeVinculoAdministrativo([
                'unidade_id' => $unidade->id,
                'dre_id' => $dre->id,
                'dirigente_nome' => $dados['dirigente_nome'],
                'dirigente_cargo' => $dados['dirigente_cargo'],
                'document_id' => $document->id,
                'data_inicio' => $dataInicio->format('Y-m-d'),
                'data_fim' => null,
                'created_by' => $currentUserId,
            ]);
            $novo->save();

            return $novo;
        });
    }
}

