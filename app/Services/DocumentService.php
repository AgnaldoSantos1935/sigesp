<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Exception;

class DocumentService
{
    /**
     * Create a new document.
     *
     * @param array $data
     * @return Document
     */
    public function createDocument(array $data): Document
    {
        // Security: Ensure tenant_id is never manually set
        if (isset($data['tenant_id'])) {
            unset($data['tenant_id']);
        }

        // Set creator if not present
        if (! isset($data['created_by']) && auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        // Force status to DRAFT on creation
        $data['status'] = Document::STATUS_DRAFT;

        return DB::transaction(function () use ($data) {
            return Document::create($data);
        });
    }

    /**
     * Upload a file for a document with automatic versioning.
     *
     * @param Document $document
     * @param UploadedFile $file
     * @return DocumentFile
     * @throws Exception
     */
    public function uploadFile(Document $document, UploadedFile $file): DocumentFile
    {
        if ($document->status === Document::STATUS_REVOKED) {
            throw new Exception("Cannot upload files to a REVOKED document.");
        }

        return DB::transaction(function () use ($document, $file) {
            $tenantId = currentTenant()->id;
            $path = "documents/{$tenantId}/{$document->id}";
            
            // Calculate hash
            $hash = hash_file('sha256', $file->getRealPath());
            
            // Store file using public disk
            $storedPath = $file->store($path, 'public');

            // Determine version
            $currentVersion = $document->files()->max('version') ?? 0;
            $newVersion = $currentVersion + 1;

            // Unset previous current files
            $document->files()->where('is_current', true)->update(['is_current' => false]);

            // Create new file record
            return DocumentFile::create([
                'document_id' => $document->id,
                'file_path' => $storedPath,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
                'file_hash' => $hash,
                'version' => $newVersion,
                'is_current' => true,
                'uploaded_by' => auth()->id(),
            ]);
        });
    }

    /**
     * Change the status of a document.
     *
     * @param Document $document
     * @param string $status
     * @return Document
     * @throws ValidationException
     */
    public function changeStatus(Document $document, string $status): Document
    {
        $allowedStatuses = [
            Document::STATUS_DRAFT,
            Document::STATUS_VALID,
            Document::STATUS_REVOKED,
            Document::STATUS_EXPIRED,
        ];

        if (! in_array($status, $allowedStatuses)) {
            throw ValidationException::withMessages([
                'status' => "Invalid status: {$status}. Allowed: " . implode(', ', $allowedStatuses),
            ]);
        }

        // Validate Transitions
        $currentStatus = $document->status;
        $isValidTransition = false;

        if ($currentStatus === Document::STATUS_DRAFT) {
            // DRAFT -> VALID or REVOKED
            if (in_array($status, [Document::STATUS_VALID, Document::STATUS_REVOKED])) {
                $isValidTransition = true;
            }
        } elseif ($currentStatus === Document::STATUS_VALID) {
            // VALID -> REVOKED or EXPIRED
            if (in_array($status, [Document::STATUS_REVOKED, Document::STATUS_EXPIRED])) {
                $isValidTransition = true;
            }
        }
        // REVOKED and EXPIRED are terminal states (no transitions allowed)

        // If status is same, it's technically valid (no-op) but strict transition might block it.
        // Let's allow same status to be set (idempotent) or block?
        // Instruction says "Proibir transições inválidas".
        // If I am DRAFT and want to go to DRAFT, is it invalid?
        // Usually safe to allow.
        if ($currentStatus === $status) {
            $isValidTransition = true;
        }

        if (! $isValidTransition) {
             throw ValidationException::withMessages([
                'status' => "Invalid status transition from {$currentStatus} to {$status}.",
            ]);
        }

        $document->status = $status;

        if ($status === Document::STATUS_VALID && auth()->check()) {
            $document->approved_by = auth()->id();
            $document->approved_at = now();
        }

        $document->save();

        return $document;
    }

    /**
     * Link a document to another model.
     *
     * @param Document $document
     * @param Model $model
     * @param string|null $purpose
     * @return DocumentLink
     * @throws Exception
     */
    public function linkToModel(Document $document, Model $model, ?string $purpose = 'reference'): DocumentLink
    {
        // Garantir que document e model pertençam ao mesmo tenant
        // Verifica se o model tem tenant_id
        if (method_exists($model, 'getAttribute') && $model->getAttribute('tenant_id')) {
            if ($model->tenant_id != $document->tenant_id) {
                 throw new Exception("Cross-tenant linking forbidden. Document Tenant: {$document->tenant_id}, Model Tenant: {$model->tenant_id}");
            }
        }

        // Evitar vínculos duplicados
        $existingLink = DocumentLink::where('document_id', $document->id)
            ->where('linked_type', get_class($model))
            ->where('linked_id', $model->getKey())
            ->where('link_type', $purpose ?? 'reference')
            ->first();

        if ($existingLink) {
            return $existingLink;
        }

        return DocumentLink::create([
            'document_id' => $document->id,
            'linked_type' => get_class($model),
            'linked_id' => $model->getKey(),
            'link_type' => $purpose ?? 'reference',
        ]);
    }
}
