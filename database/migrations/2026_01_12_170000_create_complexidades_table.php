<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complexidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            // Vinculado ao contrato (cada contrato pode ter suas definições de complexidade)
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');

            $table->string('nome'); // Ex: Baixa, Média, Alta, Simples, Complexa
            $table->decimal('fator', 8, 2)->default(1.0); // Peso multiplicador
            $table->decimal('valor_unitario', 15, 2)->nullable(); // Valor monetário fixo (se aplicável)

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'contrato_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complexidades');
    }
};
