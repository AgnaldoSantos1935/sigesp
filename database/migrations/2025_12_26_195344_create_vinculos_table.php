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

    $table->string('nomenclatura')->nullable();
    $table->string('formacao_exigida')->nullable();
    $table->string('cargo')->nullable();
    $table->string('tipo_vinculo')->nullable();
    $table->string('regime_trabalhista')->nullable();
    
    $table->string('lotacao')->nullable();
    $table->string('matricula_funcional')->nullable();

    $table->date('ingresso')->nullable();
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
