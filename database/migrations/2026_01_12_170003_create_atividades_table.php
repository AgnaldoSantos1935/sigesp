<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            
            $table->foreignId('ordem_servico_id')->constrained('ordens_servico')->onDelete('cascade');
            $table->foreignId('complexidade_id')->constrained('complexidades');
            
            $table->string('titulo');
            $table->text('descricao')->nullable();
            
            $table->decimal('quantidade', 10, 2); // Quantidade de pontos/horas
            $table->decimal('valor_unitario', 15, 2); // Snapshot do valor unitário da complexidade no momento
            $table->decimal('valor_total', 15, 2); // Calculado: quantidade * valor_unitario
            
            $table->string('status')->default('PENDENTE'); // PENDENTE, EM_ANDAMENTO, CONCLUIDA
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'ordem_servico_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atividades');
    }
};
