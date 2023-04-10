<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use App\Models\Factura;
use App\Models\FacturaContabilidad;
use App\Models\ProductosFactura;
use App\Models\ProductosFacturaContabilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacturaContabilidadController extends Controller
{
  public function index(Request $request)
  {
    try {
      $facturaElectronica = FacturaContabilidad::with('productos')->with('cliente')->with('almacen')->with('encargado')->orderBy('created_at', 'desc')->paginate($request->input('size'));
      return response()->json([
        'is_error' => false,
        'message' => 'Las facturas electronicas se muestran',
        'data' => $facturaElectronica
      ]);
    } catch (\Exception $e) {
      return $e;
      return response()->json([
        'is_error' => true,
        'message' => 'Las facturas electronicas no se muestran',
        'error' => $e
      ]);
    }
  }

  public function create(Request $request)
  {
    try {

      DB::beginTransaction();

      // Se crea la nueva factura en estado: "pendiente_pago"
      $invoice = new FacturaContabilidad;
      $invoice->id_usuario = $request->encargado['id'];
      $invoice->referencia = "CON-";    // es la misma refeencia de cotizacion ?
      $invoice->fecha = $request->fecha;              // La fecha de facturacion es igual a la fecha de cotizacion ?
      $invoice->id_cliente = $request->cliente['id'];
      $invoice->id_almacen = $request->almacen['id'];
      $invoice->descripcion = $request->descripcion;  // Para una factura deberá ser otra descripcion ?
      $invoice->estado = 'pendiente_facturar';
      $invoice->total = $request->total - ($request->total * 0.60);
      $invoice->faltante_pago = $request->total - ($request->total * 0.60);
      $invoice->total_descuento = $request->total - ($request->total * 0.60);
      $invoice->valor_descuento = 0;
      $invoice->valor_flete = 0;
      $invoice->valor_averias = 0;
      $invoice->save();
      $invoice->referencia = $invoice->referencia . $invoice->id;
      $invoice->save();

      // Se crea un producto factura por cada uno
      for ($i = 0; $i < count($request->productos); $i++) {
        $product = new ProductosFacturaContabilidad;
        $product->id_factura = $invoice->id;
        $product->id_producto = $request->productos[$i]['id_producto'];
        $product->referencia = $request->productos[$i]['referencia'];
        $product->nombre = $request->productos[$i]['nombre'];
        $product->cantidad = $request->productos[$i]['cantidad'];
        $product->valor_total_unidad = $request->productos[$i]['valor_unidad'] - ($request->productos[$i]['valor_unidad'] * 0.60);
        $product->valor_iva = $product->valor_total_unidad * 0.19;
        $product->valor_unidad = $product->valor_total_unidad - $product->valor_iva;
        $product->valor_total = $request->productos[$i]['valor_total'] - ($request->productos[$i]['valor_total'] * 0.60);

        $product->save();
      }

      DB::commit();

      return response()->json([
        'is_error' => false,
        'message' => 'La factura contable se creo de manera exitosa.'
      ]);
    } catch (\Exception $e) {
      return $e;
      DB::rollback();
      return response()->json([
        'is_error' => true,
        'error' => $e,
        'message' => 'Hubo un error al momento de crear la factura contable.'
      ]);
    }
  }


  public function storageSave(Request $request)
  {

    try {

      DB::beginTransaction();

      $file = $request->file('file');

      $id = $request->input('id');

      $reference = $request->input('reference');

      //indicamos que queremos guardar un nuevo archivo en el disco local
      \Storage::disk('local')->putFileAs('/PDF_FACTURACION',  $file, $reference.'.pdf');

      $invoice = FacturaContabilidad::findOrFail($id);
      $invoice->estado = 'facturado';
      $invoice->save();

      DB::commit();
      
      return response()->json([
        'is_error' => false,
        'message' => 'El documento se almaceno de manera exitosa'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }
  }

  public function storageGet(Request $request)
  {
    try {

      return response()->json(['facturas_pagadas' => 'ok']);
    } catch (\Exception $e) {
      throw $e;
    }
  }

}
