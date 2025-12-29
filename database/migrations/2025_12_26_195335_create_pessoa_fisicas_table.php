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
    Schema::create('pessoas_fisicas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

    $table->string('nome_completo');
    $table->string('nome_mae')->nullable();
    $table->string('nome_pai')->nullable();

    $table->string('cpf', 14)->unique();
    $table->string('rg')->nullable();
    $table->date('data_nascimento')->nullable();
    $table->string('genero')->nullable();

    $table->boolean('ativo')->default(true);

    $table->timestamps();
    $table->softDeletes();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_fisicas');
    }
};
