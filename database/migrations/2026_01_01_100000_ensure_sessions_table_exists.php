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
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't drop it here because it might have been created by the original migration
        // checking Schema::hasTable is safer but usually down() drops what up() created.
        // Since this is a fix migration, we can leave it or drop it.
        // Let's rely on the original migration to manage it normally, but strictly speaking
        // this migration created it if it was missing.
        Schema::dropIfExists('sessions');
    }
};
