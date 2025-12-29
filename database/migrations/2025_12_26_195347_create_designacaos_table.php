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
Schema::create('designacoes', function (Blueprint $table) {
    $table->id();

    $table->foreignId('pessoa_fisica_id')
        ->constrained('pessoas_fisicas')
        ->cascadeOnDelete();

    $table->foreignId('instrumento_juridico_id')
        ->constrained('instrumentos_juridicos')
        ->cascadeOnDelete();

    $table->enum('papel', [
        'GESTOR',
        'FISCAL_ADMIN',
        'FISCAL_TECNICO',
        'SUPLENTE'
    ]);

    $table->string('portaria');
    $table->date('data_publicacao');

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designacaos');
    }
};
