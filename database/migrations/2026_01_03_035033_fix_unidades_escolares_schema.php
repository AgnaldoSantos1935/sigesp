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
        Schema::table('unidades_escolares', function (Blueprint $table) {
            if (!Schema::hasColumn('unidades_escolares', 'endereco_id')) {
                $table->foreignId('endereco_id')->nullable()->after('regional_id')->constrained('enderecos')->onDelete('set null');
            }

            if (Schema::hasColumn('unidades_escolares', 'contatos_id') && !Schema::hasColumn('unidades_escolares', 'contato_id')) {
                $table->renameColumn('contatos_id', 'contato_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidades_escolares', function (Blueprint $table) {
            if (Schema::hasColumn('unidades_escolares', 'endereco_id')) {
                $table->dropForeign(['endereco_id']);
                $table->dropColumn('endereco_id');
            }

            if (Schema::hasColumn('unidades_escolares', 'contato_id')) {
                $table->renameColumn('contato_id', 'contatos_id');
            }
        });
    }
};
