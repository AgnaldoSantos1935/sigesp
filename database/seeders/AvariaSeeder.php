<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Avaria;
use App\Models\Unidade;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentType;
use Carbon\Carbon;

class AvariaSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        $laudoType = DocumentType::firstOrCreate(['slug' => 'laudo_tecnico'], ['name' => 'Laudo Técnico']);
        
        $unidades = Unidade::all();
        $user = User::first(); 

        if ($unidades->isEmpty()) return;

        foreach ($unidades as $index => $unidade) {
            // Documento de evidência
            $doc = Document::create([
                'document_type_id' => $laudoType->id,
                'numero' => 'LAUDO-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'data_emissao' => Carbon::now()->subDays(rand(1, 30)),
                'descricao_resumida' => 'Relato de avaria na unidade ' . $unidade->sigla,
                'status' => 'VALID',
                'tenant_id' => 1,
            ]);

            Avaria::create([
                'unidade_id' => $unidade->id,
                'user_id' => $user->id,
                'equipamento' => 'Computador Desktop Dell Optiplex',
                'patrimonio' => 'PAT-' . rand(10000, 99999),
                'descricao_problema' => 'Não liga, fonte queimada provável.',
                'prioridade' => 'ALTA',
                'status' => 'ABERTO',
                'document_id' => $doc->id,
                'tenant_id' => 1,
            ]);
        }
    }
}
