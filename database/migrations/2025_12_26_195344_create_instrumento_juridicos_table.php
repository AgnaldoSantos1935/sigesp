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
Schema::create('instrumentos_juridicos', function (Blueprint $table) {
    $table->id();

    $table->foreignId('licitacao_id')
        ->nullable()
        ->constrained('licitacoes');

    $table->foreignId('pessoa_juridica_id')
        ->constrained('pessoas_juridicas');

    $table->string('tipo'); // contrato, convenio, termo
    $table->string('numero');
    $table->string('processo_administrativo');

    $table->string('objeto');

    $table->decimal('valor_global', 15, 2);
    $table->date('vigencia_inicio');
    $table->date('vigencia_fim');

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumento_juridicos');
    }
};
