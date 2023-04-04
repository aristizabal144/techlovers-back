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

  public function show($id)
  {
    try {
      $factura = Factura::with('productos')->with('cliente')->with('almacen')->with('encargado')->find($id);

      return response()->json([
        'is_error' => false,
        'message' => 'La factura seleccionada, se ha encontrado',
        'data' => $factura
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'La factura seleccionada NO, se ha encontrado'
      ]);
    }
  }

  public function pagarTotalidadFactura(Request $request)
  {

    try {

      DB::beginTransaction();

      $factura = Factura::findOrFail($request->id_factura);

      $factura->total_descuento = $factura->total - ($request->valor_descuento + $request->valor_flete + $request->valor_averias);
      $factura->valor_descuento = $request->valor_descuento;
      $factura->valor_flete = $request->valor_flete;
      $factura->valor_averias = $request->valor_averias;
      $factura->faltante_pago = 0;
      $factura->estado = 'pagado';
      $factura->fecha_pago = Carbon::now();


      $factura->save();

      $carbon = new \Carbon\Carbon();

      $abono = new Abonos();

      $abonosYaRealizados = Abonos::where('id_factura', $request->id_factura)->sum('valor');

      $abono->id_factura = $request->id_factura;
      $abono->estado = 'efectivo';
      $abono->fecha = $carbon->now();
      $abono->valor = $factura->total_descuento - $abonosYaRealizados;
      $abono->descripcion = 'Ultimo pago';

      $abono->save();

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'is_error' => true,
        'message' => 'El pago no se pudo realizar con exito'
      ]);
    }
  }

  public function destroy($id)
  {
    try {
      DB::beginTransaction();

      $product_factura = ProductosFactura::where('id_factura', $id);
      $products_get = $product_factura->get();
      $articulo = new ArticulosController;
      $articulo->handleProductAmount($products_get, 'factura');
      $product_factura->delete();

      Factura::where('id', $id)->delete();

      DB::commit();
      return response()->json([
        'is_error' => false,
        'message' => 'La factura se ha eliminado correctamente',
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'is_error' => true,
        'error' => $e,
        'message' => 'Hubo un error al momento de eliminar la factura'
      ]);
    }
  }


  public function searchByParams(Request $request)
  {
    try {
      $referencia = $request->input('input');
      $facturas = Factura::with('productos')->with('cliente')->with('almacen')->with('encargado')->where('referencia', 'like', "%$referencia%")
        ->orderBy('created_at', 'desc')
        ->paginate($request->input('size'));

      return response()->json($facturas);
    } catch (\Exception $e) {
      throw $e;
    }
  }


  //----------------------------------------------------------------
  //|                   DASHBOARD FUNCTIONS                        |
  //----------------------------------------------------------------

  public function searchBulletInformation(Request $request)
  {
    try {
      $desde = $request->input('from');
      $hasta = $request->input('to');


      $facturasPagadas = Factura::where('estado', 'pagado')->whereDate('fecha_pago', '>=', $desde)->whereDate('fecha_pago', '<=', $hasta)->sum('total_descuento');
      $cartera = Factura::where('estado', 'pendiente_pago')->sum('total_descuento');


      return response()->json(['facturas_pagadas' => $facturasPagadas, 'cartera_pendiente' => $cartera]);
    } catch (\Exception $e) {
      throw $e;
    }
  }

  public function searchInvoicePayments(Request $request)
  {
    try {
      $desde = $request->input('from');
      $hasta = $request->input('to');

      $facturasPagadas = Factura::whereDate('fecha_pago', '>=', $desde)->whereDate('fecha_pago', '<=', $hasta)->where('estado', 'pagado')->get();

      return response()->json(['facturas_pagadas' => $facturasPagadas]);
    } catch (\Exception $e) {
      throw $e;
    }
  }

  public function storageSave(Request $request)
  {

    return response()->json(['base64PDF' => base64_encode(\Storage::get('cris.pdf'))]);

    try {
      //obtenemos el campo file definido en el formulario
      $file = $request->file('file');

      //obtenemos el nombre del archivo
      $nombre = $file->getClientOriginalName();

      //indicamos que queremos guardar un nuevo archivo en el disco local
      \Storage::disk('local')->putFileAs('.',  $file, $nombre);

      return response()->json("archivo guardado");
    } catch (\Exception $e) {
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
