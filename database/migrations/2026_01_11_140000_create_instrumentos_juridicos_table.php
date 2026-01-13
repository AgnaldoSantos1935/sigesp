<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrumentos_juridicos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('tipo'); // ex: EDITAL, DISPENSA, INEXIGIBILIDADE, ATA_RP
            $table->string('numero');
            $table->integer('ano');
            $table->text('objeto');
            $table->unsignedBigInteger('document_id'); // documento formal
            $table->string('status')->default('ATIVO');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('restrict');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumentos_juridicos');
    }
};
