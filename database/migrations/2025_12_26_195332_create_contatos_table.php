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
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();
            $table->string('telefone_fixo')->nullable();
            $table->string('celular_1')->nullable();
            $table->string('celular_2')->nullable();
            $table->string('rede_social_1')->nullable();
            $table->string('rede_social_2')->nullable();

            $table->foreignId('pessoa_fisica_id')
                ->nullable()
                ->constrained('pessoas_fisicas')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contatos');
    }
};
