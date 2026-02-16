<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    public $table = 'confirmations';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_usuario',
        'referencia',
        'fecha',
        'id_cliente',
        'id_almacen',
        'descripcion',
        'estado'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function almacen() {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id');
    }

    public function productos() {
        return $this->hasMany(ProductosConfirmation::class, 'id_confirmation', 'id');
    }
}
