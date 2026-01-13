<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empenhos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            // Vínculo com Item Contratual (Fonte do Recurso)
            $table->foreignId('contrato_item_id')->constrained('contrato_itens');
            
            $table->string('numero'); // Ex: 2024NE000123
            $table->integer('ano');
            $table->date('data_emissao');
            $table->decimal('valor', 15, 2); // Valor Empenhado
            $table->text('descricao')->nullable(); // Histórico/Descrição
            
            $table->string('tipo')->default('ORDINARIO'); // ORDINARIO, GLOBAL, ESTIMATIVO
            $table->string('status')->default('ATIVO'); // ATIVO, CANCELADO, ANULADO_PARCIAL

            // Nota de Empenho (Obrigatório)
            $table->foreignId('document_id')->constrained('documents');

            $table->timestamps();
            $table->softDeletes();
            
            // Unicidade do número por ano e tenant
            $table->unique(['tenant_id', 'numero', 'ano']);

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empenhos');
    }
};
