<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('necessidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            $table->foreignId('unidade_id')->constrained('unidades');
            $table->foreignId('user_id')->constrained('users'); // Solicitante

            $table->string('categoria'); // Hardware, Software, Conectividade, Mobiliário
            $table->string('descricao');
            $table->integer('quantidade_estimada')->default(1);
            
            $table->string('prioridade')->default('MEDIA'); // BAIXA, MEDIA, ALTA
            $table->string('status')->default('PENDENTE'); // PENDENTE, APROVADO, REJEITADO, ATENDIDO

            // Documento (Ofício de Solicitação)
            $table->foreignId('document_id')->nullable()->constrained('documents');

            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'unidade_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('necessidades');
    }
};
