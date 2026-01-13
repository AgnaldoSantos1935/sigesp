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
        Schema::create('unidade_vinculos_administrativos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('unidade_id')->index();
            $table->unsignedBigInteger('dre_id')->index();
            
            $table->string('dirigente_nome');
            $table->string('dirigente_cargo');
            
            $table->unsignedBigInteger('document_id')->index(); // Portaria
            
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            // SoftDeletes opcional, mas recomendado para evitar perda de histórico
            $table->softDeletes(); 

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('restrict');
            $table->foreign('dre_id')->references('id')->on('dres')->onDelete('restrict');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidade_vinculos_administrativos');
    }
};
