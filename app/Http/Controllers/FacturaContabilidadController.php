<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use App\Models\Factura;
use App\Models\FacturaContabilidad;
use App\Models\ProductosFactura;
use App\Models\ProductosFacturaContabilidad;
use App\Http\Controllers\ArticulosController;
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

      $valorProcentaje = $request->descuentoInput/100; 

      // Se crea la nueva factura en estado: "pendiente_pago"
      $invoice = new FacturaContabilidad;
      $invoice->id_usuario = $request->encargado['id'];
      $invoice->referencia = "CON-";    // es la misma refeencia de cotizacion ?
      $invoice->fecha = $request->fecha;              // La fecha de facturacion es igual a la fecha de cotizacion ?
      $invoice->id_cliente = $request->cliente['id'];
      $invoice->id_almacen = $request->almacen['id'];
      $invoice->descripcion = $request->descripcion;  // Para una factura deberá ser otra descripcion ?
      $invoice->estado = 'pendiente_facturar';
      $invoice->total = ($request->total * $valorProcentaje) / 1.19;
      $invoice->faltante_pago = ($request->total * $valorProcentaje) / 1.19;
      $invoice->iva = $invoice->total * 0.19;
      $invoice->total_iva = $invoice->total + $invoice->iva;
      $invoice->total_descuento = $request->total - ($request->total * $valorProcentaje);
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
        $product->valor_unidad = ($request->productos[$i]['valor_unidad'] * $valorProcentaje) / 1.19;
        $product->valor_iva = $product->valor_unidad * 0.19;
        $product->valor_total_unidad = $product->valor_unidad + $product->valor_iva;
        $product->valor_total = $product->cantidad * $product->valor_unidad;

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

  public function show(Request $request, $id) {
    try {
          $invoice = FacturaContabilidad::with('productos.producto')->with('cliente')->with('almacen')->with('encargado')->find($id);

          return response()->json([
              'is_error' => false,
              'message' => 'La factura electronica seleccionada se ha encontrado',
              'data' => $invoice
          ]);
      } catch (\Exception $e) {
          return response()->json([
              'is_error' => true,
              'message' => 'La factura electronica seleccionda NO se ha encontrado'
          ]);
      }
  }

  public function update(Request $request, $id)
  {
      try {

          DB::beginTransaction();

          $invoice = FacturaContabilidad::findOrFail($id);

          $invoice->referencia = $request->referencia;
          $invoice->fecha = $request->fecha;
          $invoice->id_cliente = $request->cliente['id'];
          $invoice->id_almacen = $request->almacen['id'];
          $invoice->total = $request->total;
          $invoice->iva = $invoice->total * 0.19;
          $invoice->total_iva = $invoice->total + $invoice->iva;
          $invoice->descripcion = $request->descripcion;

          $invoice->save();

          ProductosFacturaContabilidad::where('id_factura', $request->id)->delete();

          for ($i = 0; $i < count($request->productos); $i++) {

              $product = new ProductosFacturaContabilidad;
              $product->id_factura = $invoice->id;
              $product->id_producto = $request->productos[$i]['producto']['id'];
              $product->referencia = $request->productos[$i]['referencia'];
              $product->nombre = $request->productos[$i]['nombre'];
              $product->cantidad = $request->productos[$i]['cantidad'];
              $product->valor_unidad = $request->productos[$i]['valor_unidad'];
              $product->valor_total = $request->productos[$i]['valor_total'];
              $product->valor_total_unidad = $request->productos[$i]['valor_total_unidad'];
              $product->valor_iva = $request->productos[$i]['valor_iva'];

              $product->save();
          }

          DB::commit();

          return response()->json([
            'is_error' => false,
            'message' => 'La factura electronica se a actualizado bien',
            'data' => $invoice
        ]);
      } catch (\Exception $e) {
          return $e;
          DB::rollback();
          return response()->json([
              'is_error' => true,
              'message' => 'Hubo un error al momento de actualizar la factura electronica'
          ]);
      }
  }

  public function destroy($id)
    {
        try {
            DB::beginTransaction();
    
            $product_factura = ProductosFacturaContabilidad::where('id_factura', $id);
            $products_get = $product_factura->get();

            $invoice = FacturaContabilidad::findOrFail($id);

            if($invoice['estado'] != 'pendiente_facturar'){
              $articulo = new ArticulosController;
              $articulo->handleProductAmountContabilidad($products_get, 'suma');
            }

            $product_factura->delete();
    
            FacturaContabilidad::where('id', $id)->delete();
    
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


  public function storageSave(Request $request)
  {

    try {

      DB::beginTransaction();

      $file = $request->file('file');

      $id = $request->input('id');

      $reference = $request->input('reference');

      //indicamos que queremos guardar un nuevo archivo en el disco local
      \Storage::disk('local')->putFileAs('/PDF_FACTURACION',  $file, $reference.'.pdf');

      $invoice = FacturaContabilidad::with('productos')->findOrFail($id);
      $invoice->estado = 'facturado';
      $invoice->save();


      $articulo = new ArticulosController;
      $articulo->handleProductAmountContabilidad($invoice['productos'], 'resta');

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

      return response()->json(['base64PDF' => base64_encode(\Storage::get('PDF_FACTURACION/'.$request->input('nameFile').'.pdf'))]);
    } catch (\Exception $e) {
      throw $e;
    }
  }


  public function getFacturasArticulo($id)
  {
    try {
      $invoice = ProductosFacturaContabilidad::with('factura.productos')->with('factura.cliente')->with('factura.almacen')->with('factura.encargado')->where('id_producto', $id)->orderBy('created_at', 'desc')->get();

      return response()->json([
        'is_error' => false,
        'message' => 'Los productos se muestran',
        'data' => $invoice
      ]);
    } catch (\Exception $e) {
      throw $e;
    }
  }

}
