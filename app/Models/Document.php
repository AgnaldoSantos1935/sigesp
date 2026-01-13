<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use BelongsToTenant, SoftDeletes;

    // Constantes para Status
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_VALID = 'VALID';
    const STATUS_REVOKED = 'REVOKED';
    const STATUS_EXPIRED = 'EXPIRED';

    protected $table = 'documents';

    protected $fillable = [
        'document_type_id',
        'numero',
        'data_emissao',
        'descricao_resumida',
        'conteudo_texto',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'approved_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function files()
    {
        return $this->hasMany(DocumentFile::class);
    }

    public function links()
    {
        return $this->hasMany(DocumentLink::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

