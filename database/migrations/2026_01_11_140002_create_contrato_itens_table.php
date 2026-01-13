<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrato_itens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('contrato_id');
            $table->text('descricao');
            $table->string('unidade_medida');
            $table->decimal('quantidade_contratada', 15, 4);
            $table->decimal('valor_unitario', 15, 4);
            $table->decimal('valor_total', 15, 2);
            $table->string('controle_execucao'); // GLOBAL | POR_ITEM
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrato_itens');
    }
};
