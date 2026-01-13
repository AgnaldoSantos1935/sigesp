<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DocumentLink extends Model
{
    use BelongsToTenant;

    protected $table = 'document_links';

    protected $fillable = [
        'document_id',
        'linked_type',
        'linked_id',
        'link_type',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function linked()
    {
        return $this->morphTo();
    }
}

