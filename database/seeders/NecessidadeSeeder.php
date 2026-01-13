<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Necessidade;
use App\Models\Unidade;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentType;
use Carbon\Carbon;

class NecessidadeSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $oficioType = DocumentType::firstOrCreate(['slug' => 'oficio_solicitacao'], ['name' => 'Ofício de Solicitação']);

        $unidades = Unidade::all();
        $user = User::first();

        if ($unidades->isEmpty()) return;

        foreach ($unidades as $index => $unidade) {
            // Documento
            $doc = Document::create([
                'document_type_id' => $oficioType->id,
                'numero' => 'OFICIO-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT) . '/2024',
                'data_emissao' => Carbon::now()->subDays(rand(1, 60)),
                'descricao_resumida' => 'Solicitação de melhoria infraestrutura TI',
                'status' => 'VALID',
                'tenant_id' => 1,
            ]);

            Necessidade::create([
                'unidade_id' => $unidade->id,
                'user_id' => $user->id,
                'categoria' => 'Conectividade',
                'descricao' => 'Instalação de pontos de rede adicionais na sala dos professores.',
                'quantidade_estimada' => 5,
                'prioridade' => 'MEDIA',
                'status' => 'PENDENTE',
                'document_id' => $doc->id,
                'tenant_id' => 1,
            ]);
        }
    }
}
