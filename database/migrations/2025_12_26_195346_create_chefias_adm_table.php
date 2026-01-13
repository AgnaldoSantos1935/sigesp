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
        Schema::create('chefias_adm', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('pessoa_fisica_id')
                ->constrained('pessoas_fisicas')
                ->cascadeOnDelete();

            $table->string('titulo')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_final')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chefias_adm');
    }
};
