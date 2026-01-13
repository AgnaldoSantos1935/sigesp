<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('pessoas_juridicas', function (Blueprint $table) {
    $table->id();

    $table->foreignId('endereco_id')->nullable()->constrained('enderecos')->nullOnDelete();
    $table->foreignId('contato_id')->nullable()->constrained('contatos')->nullOnDelete();

    $table->string('razao_social');
    $table->string('nome_fantasia')->nullable();
    $table->string('cnpj', 18)->unique();

    $table->string('cod_cnae')->nullable();
    $table->string('ramo_atividade')->nullable();
    $table->string('cod_natjuridica')->nullable();
    $table->string('tipo_pessoa')->nullable();

    $table->boolean('ativo')->default(true);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_juridicas');
    }
};
