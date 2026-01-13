<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            
            // Medição vinculada à OS (que gera valor financeiro)
            $table->foreignId('ordem_servico_id')->constrained('ordens_servico');
            
            $table->date('data_medicao');
            $table->date('periodo_inicio');
            $table->date('periodo_fim');
            
            $table->decimal('valor_medido', 15, 2); // Valor total a ser pago
            
            $table->string('status')->default('RASCUNHO'); // RASCUNHO, ENVIADA, ATESTADA, REJEITADA, PROCESSADA
            
            // Documento comprobatório (Relatório de Medição)
            $table->foreignId('document_id')->nullable()->constrained('documents');
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'ordem_servico_id']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicoes');
    }
};
