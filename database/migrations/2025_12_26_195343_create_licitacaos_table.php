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
Schema::create('licitacoes', function (Blueprint $table) {
    $table->id();

    $table->string('numero_licitacao')->nullable();
    $table->string('numero_processo')->nullable();
    $table->string('numero_edital')->nullable();

    $table->string('modalidade');
    $table->string('objeto');
    $table->string('criterios')->nullable();
    $table->string('habilitacao')->nullable();

    $table->string('fundamento_legal')->nullable();
    $table->date('data_publicacao')->nullable();
    $table->date('data_encerramento')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacaos');
    }
};
