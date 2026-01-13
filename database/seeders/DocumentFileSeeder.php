<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentFileSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) return;

        $admin = User::where('email', 'admin@demo.com')->first();
        $adminId = $admin ? $admin->id : null;

        $documents = Document::where('tenant_id', $tenant->id)->get();

        foreach ($documents as $doc) {
            // Create a main file for each document
            DocumentFile::create([
                'tenant_id' => $tenant->id,
                'document_id' => $doc->id,
                'file_path' => 'documents/' . $tenant->id . '/' . $doc->id . '/arquivo_v1.pdf',
                'file_name' => 'arquivo_original.pdf',
                'mime_type' => 'application/pdf',
                'size_bytes' => 1024 * rand(100, 5000), // 100KB to 5MB
                'file_hash' => md5($doc->id . 'v1'),
                'version' => 1,
                'is_current' => true,
                'uploaded_by' => $adminId,
                'created_at' => $doc->created_at,
            ]);

            // If it's the specific Contract, add an older version
            if (str_contains($doc->numero, 'CONT-001/2023')) {
                // Update previous file to not current
                $currentFile = DocumentFile::where('document_id', $doc->id)->first();
                $currentFile->update(['is_current' => false]);

                // Add new version
                DocumentFile::create([
                    'tenant_id' => $tenant->id,
                    'document_id' => $doc->id,
                    'file_path' => 'documents/' . $tenant->id . '/' . $doc->id . '/arquivo_v2_assinado.pdf',
                    'file_name' => 'contrato_assinado_final.pdf',
                    'mime_type' => 'application/pdf',
                    'size_bytes' => 1024 * rand(2000, 6000),
                    'file_hash' => md5($doc->id . 'v2'),
                    'version' => 2,
                    'is_current' => true,
                    'uploaded_by' => $adminId,
                    'created_at' => $doc->created_at->addDay(),
                ]);
            }
        }
    }
}
