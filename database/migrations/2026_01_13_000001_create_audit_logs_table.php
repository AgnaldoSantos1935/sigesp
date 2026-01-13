<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index(); // Nullable for global actions
            
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN, SWITCH_TENANT
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
