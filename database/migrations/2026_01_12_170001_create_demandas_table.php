<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            
            // Polymorphic Demandante (Unidade, User, Setor, etc.)
            $table->morphs('demandante');
            
            $table->string('titulo');
            $table->text('descricao');
            
            $table->string('prioridade')->default('MEDIA'); // BAIXA, MEDIA, ALTA, CRITICA
            $table->string('status')->default('RASCUNHO'); // RASCUNHO, ENVIADA, EM_ANALISE, APROVADA, REJEITADA, CONVERTIDA_OS
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandas');
    }
};
