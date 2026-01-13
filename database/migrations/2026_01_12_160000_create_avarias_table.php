<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avarias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            $table->foreignId('unidade_id')->constrained('unidades');
            $table->foreignId('user_id')->constrained('users'); // Quem reportou

            $table->string('equipamento'); // Descrição curta ou nome
            $table->string('patrimonio')->nullable();
            $table->text('descricao_problema');

            $table->string('prioridade')->default('MEDIA'); // BAIXA, MEDIA, ALTA
            $table->string('status')->default('ABERTO'); // ABERTO, EM_ANALISE, RESOLVIDO, CONDENADO

            // Documento (Foto, Laudo)
            $table->foreignId('document_id')->nullable()->constrained('documents');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'unidade_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avarias');
    }
};
