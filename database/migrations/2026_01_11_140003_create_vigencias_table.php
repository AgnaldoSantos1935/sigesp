<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vigencias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('contrato_id');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('tipo'); // ORIGINAL | ADITIVO
            $table->unsignedBigInteger('document_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('restrict');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vigencias');
    }
};
