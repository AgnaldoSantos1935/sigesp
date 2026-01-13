<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrdemServico;
use App\Models\Document;
use App\Models\DocumentType;
use App\Services\MedicaoService;
use App\Services\OrdemServicoService;

class MedicaoSeeder extends Seeder
{
    public function run(): void
    {
        session(['tenant_id' => 1]);

        // Find OS in execution with activities
        $ordens = OrdemServico::where('status', 'EM_EXECUCAO')->with('atividades')->get();
        
        if ($ordens->isEmpty()) return;

        $medicaoService = app(MedicaoService::class);
        $osService = app(OrdemServicoService::class);

        $docType = DocumentType::firstOrCreate(['slug' => 'relatorio_medicao'], ['name' => 'Relatório de Medição']);

        foreach ($ordens as $os) {
            // Complete activities to allow measurement
            foreach ($os->atividades as $atividade) {
                $osService->completeAtividade($atividade);
            }

            // Create Document
            $doc = Document::create([
                'tenant_id' => 1,
                'document_type_id' => $docType->id,
                'numero' => "REL-MED-{$os->codigo}",
                'descricao_resumida' => 'Relatório de Medição - Detalhamento das atividades realizadas',
                'status' => 'VALID',
                // 'caminho_arquivo' => 'dummy/path/relatorio.pdf', // Not in fillable based on read, but was in error log? 
                // Error log said: insert into `documents` ... `caminho_arquivo` ... values ...
                // Wait, Document.php fillable did NOT have caminho_arquivo in the read snippet (lines 15-25).
                // But the SQL error SHOWED `caminho_arquivo` in the insert list!
                // "insert into `documents` (`tenant_id`, ..., `caminho_arquivo`, ...) values ..."
                // This means the model might NOT be guarding it effectively or I missed it in fillable or it is guarded = [].
                // The snippet showed: protected $fillable = [ ... ];
                // If it's not in fillable, Eloquent usually ignores it on create/fill unless unguard() is called.
                // However, the error was "Unknown column 'titulo'". It did NOT complain about 'caminho_arquivo'.
                // So 'caminho_arquivo' column EXISTS in DB, but maybe not in fillable? 
                // Or maybe I misread the fillable list or it was truncated.
                // Re-reading snippet: 
                // 15->    protected $fillable = [
                // ...
                // 25->    ];
                // It didn't show 'caminho_arquivo'.
                // But if the column exists and I try to create, if it's not fillable, it should be ignored.
                // The error was definitely about 'titulo'.
                // I will remove 'titulo' and use 'numero'.
                // I will also keep 'caminho_arquivo' if the column exists.
                // To be safe, I'll check if I should use 'numero' instead of 'titulo'.
                // SQL error: "Unknown column 'titulo'".
                'data_emissao' => now(),
            ]);

            // Create Medicao
            $medicaoData = [
                'tenant_id' => 1,
                'data_medicao' => now(),
                'periodo_inicio' => now()->subDays(30)->format('Y-m-d'),
                'periodo_fim' => now()->format('Y-m-d'),
                'document_id' => $doc->id,
            ];

            try {
                $medicaoService->createMedicao($os, $medicaoData);
                
                // Update OS status to ENTREGUE or MEDIDA (as per migration, MEDIDA is not there, maybe ENTREGUE?)
                // Migration said: ABERTA, EM_EXECUCAO, ENTREGUE, CANCELADA, MEDIDA (Wait, did I add MEDIDA?)
                // Checking migration 2026_01_12_170002_create_ordens_servico_table.php:
                // $table->string('status')->default('ABERTA'); // ABERTA, EM_EXECUCAO, ENTREGUE, CANCELADA, MEDIDA
                // Yes, MEDIDA is there.
                $os->status = 'MEDIDA';
                $os->save();
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
