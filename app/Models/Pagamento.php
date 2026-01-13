<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamento extends Model
{
    use BelongsToTenant, SoftDeletes;

    const STATUS_PAGO = 'PAGO';
    const STATUS_ESTORNADO = 'ESTORNADO';

    protected $table = 'pagamentos';

    protected $fillable = [
        'empenho_id',
        'numero_ordem_bancaria',
        'data_pagamento',
        'valor',
        'status',
        'document_id',
        'nota_fiscal_id',
        'observacao',
    ];

    protected $casts = [
        'data_pagamento' => 'date',
        'valor' => 'decimal:2',
    ];

    public function empenho()
    {
        return $this->belongsTo(Empenho::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function notaFiscal()
    {
        return $this->belongsTo(Document::class, 'nota_fiscal_id');
    }
}
