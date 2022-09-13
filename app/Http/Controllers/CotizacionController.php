<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Cotizacion;
use App\Models\Factura;
use App\Models\ProductosCotizacion;
use App\Models\ProductosFactura;
use Illuminate\Http\Request;
use App\Http\Controllers\ArticulosController;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{

    public function index(Request $request)
    {
        try {
            $cotizacion = Cotizacion::with('productos')->with('cliente')->with('almacen')->orderBy('created_at', 'desc')->paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Las cotizaciones se muestran',
                'data' => $cotizacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Las cotizaciones no se muestran',
                'error' => $e
            ]);
        }
    }

    public function create(Request $request)
    {
        try {

            DB::beginTransaction();

            $quote = new Cotizacion;

            $quote->referencia = $request->reference;
            $quote->fecha = $request->date;
            $quote->id_cliente = $request->customer['id'];
            $quote->id_almacen = $request->store['id'];
            $quote->total = $request->total;
            $quote->descripcion = $request->description;
            $quote->facturado = false;

            $quote->save();
            // return count($request->products);

            for ($i = 0; $i < count($request->products); $i++) {
                $product = new ProductosCotizacion;
                $product->id_cotizacion = $quote->id;
                $product->id_producto = $request->products[$i]['id'];
                $product->referencia = $request->products[$i]['referencia'];
                $product->nombre = $request->products[$i]['nombre'];
                $product->cantidad_cotizacion = $request->products[$i]['cantidad_cotizacion'];
                $product->valor_unidad = $request->products[$i]['valor_unidad'];
                $product->valor_total = $request->products[$i]['valor_total'];

                $product->save();
            }

            // Inventario

            $articulo = new ArticulosController;
            $articulo->handleProductAmount($request);


            DB::commit();


            return response()->json([
                'is_error' => false,
                'message' => 'La cotizacion fue registrado de manera correcta',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $cotizacion = Cotizacion::with('productos')->with('cliente')->with('almacen')->find($id);

            return response()->json([
                'is_error' => false,
                'message' => 'La cotizacion seleccionada, se ha encontrado',
                'data' => $cotizacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'La cotizacion seleccionada NO, se ha encontrado'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            $quote = Cotizacion::findOrFail($id);

            $quote->referencia = $request->reference;
            $quote->fecha = $request->date;
            $quote->id_cliente = $request->customer['id'];
            $quote->id_almacen = $request->store['id'];
            $quote->total = $request->total;
            $quote->descripcion = $request->description;
            $quote->facturado = false;

            $quote->save();


            for ($i = 0; $i < count($request->products); $i++) {
                DB::table('productos_cotizacions')->delete($request->products[$i]['id']);

                $product = new ProductosCotizacion;
                $product->id_cotizacion = $quote->id;
                $product->id_producto = $request->products[$i]['id'];
                $product->referencia = $request->products[$i]['referencia'];
                $product->nombre = $request->products[$i]['nombre'];
                $product->cantidad_cotizacion = $request->products[$i]['cantidad_cotizacion'];
                $product->valor_unidad = $request->products[$i]['valor_unidad'];
                $product->valor_total = $request->products[$i]['valor_total'];

                $product->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de actualizar la cotizacion'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('cotizacions')->delete($id);

            $product_cotizacion = ProductosCotizacion::where('id_cotizacion', $id);
            $product_cotizacion->delete();

            return response()->json([
                'is_error' => false,
                'message' => 'La cotizacion se ha eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error eliminando la cotizacion'
            ]);
        }
    }

    public function check(Request $request)
    {
        try {

            DB::beginTransaction();

            // Se actualiza el estado de la cotizacion a facturado
            $cotizacion = Cotizacion::with('productos')->findOrFail($request->id);
            $cotizacion->facturado = true;

            $cotizacion->save();

            // Se crea la nueva factura en estado: "pendiente_pago"
            $invoice = new Factura;
            $invoice->referencia = $cotizacion->referencia;    // es la misma refeencia de cotizacion ?
            $invoice->fecha = $cotizacion->fecha;              // La fecha de facturacion es igual a la fecha de cotizacion ?
            $invoice->id_cliente = $cotizacion->id_cliente;
            $invoice->id_almacen = $cotizacion->id_almacen;
            $invoice->descripcion = $cotizacion->descripcion;  // Para una factura deberá ser otra descripcion ?
            $invoice->estado = 'pendiente_pago';
            $invoice->total = $cotizacion->total;
            $invoice->save();

            // Se crea un producto factura por cada uno
            for ($i = 0; $i < count($request->productos); $i++) {
                $product = new ProductosFactura;
                $product->id_factura = $invoice->id;
                $product->id_producto = $request->productos[$i]['id'];
                $product->referencia = $request->productos[$i]['referencia'];
                $product->nombre = $request->productos[$i]['nombre'];
                $product->cantidad = $request->productos[$i]['cantidad_cotizacion'];
                $product->valor_unidad = $request->productos[$i]['valor_unidad'];
                $product->valor_total = $request->productos[$i]['valor_total'];

                $product->save();
            }

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'La cotización se actualizo de manera exitosa y se creó una nueva factura'
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'error' => $e,
                'message' => 'Hubo un error al momento de actualizar la cotización y creacion de la nueva factura'
            ]);
        }
    }
}
