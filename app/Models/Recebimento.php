<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recebimento extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'recebimentos';

    protected $fillable = [
        'unidade_id',
        'contrato_item_id',
        'descricao_item',
        'quantidade',
        'data_recebimento',
        'status',
        'document_id',
        'recebido_por'
    ];

    protected $casts = [
        'data_recebimento' => 'date',
    ];

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

    public function contratoItem()
    {
        return $this->belongsTo(ContratoItem::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function recebedor()
    {
        return $this->belongsTo(User::class, 'recebido_por');
    }
}
