<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('instrumento_juridico_id');
            $table->string('numero');
            $table->integer('ano');
            $table->text('objeto');
            $table->string('fornecedor_nome');
            $table->string('fornecedor_cnpj');
            $table->string('tipo_contrato'); // AQUISICAO, SERVICO, INTERNET, FABRICA_SOFTWARE, etc.
            $table->string('status')->default('ATIVO');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
            $table->foreign('instrumento_juridico_id')->references('id')->on('instrumentos_juridicos')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
