<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Módulos do Sistema (features)
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // ex: 'financeiro', 'contratos'
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Planos SaaS
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // ex: 'basic', 'pro', 'enterprise'
            $table->text('description')->nullable();
            
            // Monetização Futura
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->decimal('price_yearly', 10, 2)->nullable();
            $table->string('currency', 3)->default('BRL');
            $table->integer('max_users')->nullable(); // null = ilimitado
            $table->integer('max_storage_mb')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Relacionamento Planos <-> Módulos
        Schema::create('plan_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['plan_id', 'module_id']);
        });

        // 4. Vincular Tenant ao Plano
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('cnpj')->constrained('plans');
            // Data de expiração ou status da assinatura poderia vir aqui ou em tabela 'subscriptions'
            // Por enquanto, simplificado no tenant, mas preparado para expansão.
            $table->date('trial_ends_at')->nullable()->after('plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'trial_ends_at']);
        });
        Schema::dropIfExists('plan_modules');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('modules');
    }
};
