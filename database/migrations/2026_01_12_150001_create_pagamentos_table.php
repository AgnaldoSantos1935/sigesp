<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();

            $table->foreignId('empenho_id')->constrained('empenhos');
            
            $table->string('numero_ordem_bancaria')->nullable();
            $table->date('data_pagamento');
            $table->decimal('valor', 15, 2);
            
            $table->string('status')->default('PAGO'); // PAGO, ESTORNADO

            // Comprovante de Pagamento (Ordem Bancária)
            $table->foreignId('document_id')->constrained('documents');

            // Nota Fiscal com Atesto (Obrigatório)
            $table->foreignId('nota_fiscal_id')->constrained('documents');

            $table->text('observacao')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'data_pagamento']);

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
