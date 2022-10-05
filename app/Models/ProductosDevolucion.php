<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductosDevolucion extends Model
{
    public $table = 'productos_devolucions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_devolucion',
        'id_producto',
        'referencia',
        'nombre',
        'cantidad',
    ];

    public function devolucion() {
        return $this->belongsTo(Devolucion::class, 'id_devolucion', 'id');
    }

    public function producto() {
        return $this->belongsTo(Articulo::class, 'id_producto', 'id');
    }
}
