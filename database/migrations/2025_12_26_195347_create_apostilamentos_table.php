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
Schema::create('apostilamentos', function (Blueprint $table) {
    $table->id();

    $table->foreignId('instrumento_juridico_id')
        ->constrained('instrumentos_juridicos')
        ->cascadeOnDelete();

    $table->string('numero');
    $table->string('objeto');

    $table->date('data_publicacao');
    $table->date('data_assinatura')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apostilamentos');
    }
};
