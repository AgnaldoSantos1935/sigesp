<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avaria extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'avarias';

    protected $fillable = [
        'unidade_id',
        'user_id',
        'equipamento',
        'patrimonio',
        'descricao_problema',
        'prioridade',
        'status',
        'document_id'
    ];

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
