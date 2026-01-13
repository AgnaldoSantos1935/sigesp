<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            
            // Origem: Demanda
            $table->foreignId('demanda_id')->constrained('demandas');
            
            // Fonte de Recurso: Item de Contrato (Fábrica de Software)
            $table->foreignId('contrato_item_id')->constrained('contrato_itens');
            
            $table->string('codigo')->nullable(); // Ex: OS-2026-001
            $table->date('data_emissao');
            $table->date('prazo_execucao')->nullable();
            
            $table->string('status')->default('ABERTA'); // ABERTA, EM_EXECUCAO, ENTREGUE, CANCELADA, MEDIDA
            $table->decimal('valor_estimado', 15, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'contrato_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens_servico');
    }
};
