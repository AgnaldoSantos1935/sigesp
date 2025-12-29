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
Schema::create('vinculos', function (Blueprint $table) {
    $table->id();

    $table->foreignId('pessoa_fisica_id')
        ->constrained('pessoas_fisicas')
        ->cascadeOnDelete();

    $table->string('cargo');
    $table->string('tipo_vinculo');
    $table->string('regime_trabalhista')->nullable();

    $table->date('ingresso');
    $table->date('encerramento')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculos');
    }
};
