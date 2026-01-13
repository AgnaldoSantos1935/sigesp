<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empenho extends Model
{
    use BelongsToTenant, SoftDeletes;

    const STATUS_ATIVO = 'ATIVO';
    const STATUS_CANCELADO = 'CANCELADO';
    const STATUS_ANULADO_PARCIAL = 'ANULADO_PARCIAL';
    const STATUS_ENCERRADO = 'ENCERRADO';

    const TIPO_ORDINARIO = 'ORDINARIO';
    const TIPO_GLOBAL = 'GLOBAL';
    const TIPO_ESTIMATIVO = 'ESTIMATIVO';

    protected $table = 'empenhos';

    protected $fillable = [
        'contrato_item_id',
        'numero',
        'ano',
        'data_emissao',
        'valor',
        'descricao',
        'tipo',
        'status',
        'document_id',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'valor' => 'decimal:2',
    ];

    public function contratoItem()
    {
        return $this->belongsTo(ContratoItem::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function getValorPagoAttribute()
    {
        return $this->pagamentos()->where('status', 'PAGO')->sum('valor');
    }

    public function getSaldoAttribute()
    {
        return $this->valor - $this->valor_pago;
    }
}
