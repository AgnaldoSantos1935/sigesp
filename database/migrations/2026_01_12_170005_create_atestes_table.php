<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atestes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            
            $table->foreignId('medicao_id')->constrained('medicoes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Usuário que realizou o ateste (Gestor/Fiscal)
            
            $table->date('data_ateste');
            $table->string('status')->default('APROVADO'); // APROVADO, REJEITADO, APROVADO_RESSALVAS
            
            $table->text('observacoes')->nullable(); // Justificativa ou ressalvas
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'medicao_id']);
            $table->index(['tenant_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atestes');
    }
};
