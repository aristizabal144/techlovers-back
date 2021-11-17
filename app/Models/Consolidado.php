<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consolidado extends Model
{
    public $table = 'consolidados';

    protected $primaryKey = 'idConsolidados';

    protected $fillable = [
        'id_consolidados','referencia_consolidado','fecha_pedido','fecha_salida','fecha_llegada','valor_flete','valor_RMB','metros_cubicos','subtotal_RMB','total'
    ];
}
