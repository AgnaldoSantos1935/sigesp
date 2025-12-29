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
  Schema::create('pessoa_juridica_representantes', function (Blueprint $table) {
    $table->id();

    $table->foreignId('pessoa_juridica_id')
        ->constrained('pessoas_juridicas')
        ->cascadeOnDelete();

    $table->foreignId('pessoa_fisica_id')
        ->constrained('pessoas_fisicas')
        ->cascadeOnDelete();

    $table->enum('tipo', ['LEGAL', 'PROCURADOR', 'TECNICO']);
    $table->date('inicio_vigencia');
    $table->date('fim_vigencia')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_juridica_representantes');
    }
};
