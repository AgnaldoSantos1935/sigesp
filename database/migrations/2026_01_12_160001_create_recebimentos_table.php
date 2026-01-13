<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recebimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            $table->foreignId('unidade_id')->constrained('unidades');
            
            // Link com item de contrato (se aplicável)
            $table->foreignId('contrato_item_id')->nullable()->constrained('contrato_itens');
            
            $table->string('descricao_item'); 
            $table->integer('quantidade');
            $table->date('data_recebimento');
            
            $table->string('status')->default('RECEBIDO'); // RECEBIDO, CONFERIDO, DEVOLVIDO
            
            // Documento (Guia de Remessa, Nota Fiscal)
            $table->foreignId('document_id')->nullable()->constrained('documents');

            $table->foreignId('recebido_por')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'unidade_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recebimentos');
    }
};
