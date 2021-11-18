<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consolidado extends Model
{
    public $table = 'consolidados';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id','referencia_consolidado','fecha_pedido','fecha_salida','fecha_llegada','valor_flete','valor_RMB','metros_cubicos','subtotal_RMB','total'
    ];
}
