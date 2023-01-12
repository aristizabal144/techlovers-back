<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use App\Models\Factura;
use App\Models\ProductosFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $factura = Factura::with('productos')->with('cliente')->with('almacen')->with('encargado')->orderBy('created_at', 'desc')->paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Las facturas se muestran',
                'data' => $factura
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Las facturas no se muestran',
                'error' => $e
            ]);
        }
    }

    // ESTE METODO SE REPLICA EN LA ACTUALIZACION DE LAS COTIZACIONES
    // LA CREACION DE UNA FACTURA SOLO SE DÁ CUANDO SE PASA DE UNA COTIZACION A FACTURADO!!!!
    public function create(Request $request)
    {
        try {

            DB::beginTransaction();

            $invoice = new Factura;

            $invoice->referencia = $request->reference;
            $invoice->fecha = $request->date;
            $invoice->id_cliente = $request->customer['id'];
            $invoice->id_almacen = $request->store['id'];
            $invoice->descripcion = $request->descripcion;
            $invoice->estado = $request->estado;
            $invoice->total = $request->total;


            $invoice->save();
            // return count($request->products);

            for ($i = 0; $i < count($request->products); $i++) {
                $product = new ProductosFactura;

                $product->id_factura = $invoice->id;
                $product->id_producto = $request->products[$i]['id'];
                $product->referencia = $request->products[$i]['referencia'];
                $product->nombre = $request->products[$i]['nombre'];
                $product->cantidad = $request->products[$i]['cantidad'];
                $product->valor_unidad = $request->products[$i]['valor_unidad'];
                $product->valor_total = $request->products[$i]['valor_total'];

                $product->save();
            }

            DB::commit();


            return response()->json([
                'is_error' => false,
                'message' => 'La factura fue registrado de manera correcta',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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

    public function pagarTotalidadFactura(Request $request){

        try {

            DB::beginTransaction();

            $factura = Factura::findOrFail($request->id_factura);

            $factura->total_descuento = $factura->total - ($request->valor_descuento + $request->valor_flete + $request->valor_averias);
            $factura->valor_descuento = $request->valor_descuento;
            $factura->valor_flete = $request->valor_flete;
            $factura->valor_averias = $request->valor_averias;
            $factura->faltante_pago = 0;
            $factura->estado = 'pagado';

    
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
            DB::table('facturas')->delete($id);

            $product_factura = ProductosFactura::where('id_factura', $id);
            $product_factura->delete();

            return response()->json([
                'is_error' => false,
                'message' => 'La factura se ha eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error eliminando la factura'
            ]);
        }
    }
}
