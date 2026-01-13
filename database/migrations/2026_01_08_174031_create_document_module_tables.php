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
        // 1. Tipos de Documentos (Global - Sem tenant_id)
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // ex: 'contrato', 'portaria', 'nota-fiscal'
            $table->string('description')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Documentos (Fatos Administrativos - Com tenant_id)
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index(); // Isolamento SaaS
            $table->foreignId('document_type_id')->constrained('document_types');

            $table->string('numero')->nullable(); // Número oficial do documento
            $table->date('data_emissao');
            $table->text('descricao_resumida');
            $table->longText('conteudo_texto')->nullable(); // Texto indexável/buscável

            // Status do Documento
            $table->enum('status', ['DRAFT', 'VALID', 'REVOKED', 'EXPIRED'])->default('DRAFT');

            // Metadados de Autoria
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'document_type_id']);
        });

        // 3. Arquivos do Documento (Com versionamento e tenant_id)
        Schema::create('document_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');

            $table->string('file_path'); // Caminho no Storage
            $table->string('file_name'); // Nome original
            $table->string('mime_type');
            $table->bigInteger('size_bytes');
            $table->string('file_hash')->nullable(); // SHA-256 para integridade

            $table->integer('version')->default(1);
            $table->boolean('is_current')->default(true); // Flag para versão atual

            $table->foreignId('uploaded_by')->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'document_id', 'version']);
        });

        // 4. Vínculos Polimórficos (Documento <-> Qualquer coisa)
        Schema::create('document_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');

            // Polimorfismo (linked_id, linked_type)
            // Ex: linked_type = 'App\Models\Contrato', linked_id = 1
            $table->morphs('linked');

            $table->string('link_type')->default('reference'); // 'reference', 'attachment', 'origin'

            $table->timestamps();

            $table->index(['tenant_id', 'linked_type', 'linked_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_links');
        Schema::dropIfExists('document_files');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_types');
    }
};
