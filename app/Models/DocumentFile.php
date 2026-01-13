<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    use BelongsToTenant;

    protected $table = 'document_files';

    protected $fillable = [
        'document_id',
        'file_path',
        'file_name',
        'mime_type',
        'size_bytes',
        'file_hash',
        'version',
        'is_current',
        'uploaded_by',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'size_bytes' => 'integer',
        'version' => 'integer',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
