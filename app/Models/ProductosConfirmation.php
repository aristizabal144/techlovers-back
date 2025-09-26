<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductosConfirmation extends Model
{
    public $table = 'productos_confirmations';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_confirmation',
        'id_producto',
        'referencia',
        'codigo_barras',
        'nombre',
        'cantidad',
        'cantidad_confirmacion',
        'estado'
    ];

    public function confirmation() {
        return $this->belongsTo(Confirmation::class, 'id_confirmation', 'id');
    }

    public function producto() {
        return $this->belongsTo(Articulo::class, 'id_producto', 'id');
    }
}
