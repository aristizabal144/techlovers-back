<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\ProductosFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $factura = Factura::with('productos')->with('cliente')->with('almacen')->orderBy('created_at', 'desc')->paginate($request->input('size'));
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

    // ESTE METODO DEBE DESCONTAR LOS PRODUCTOS EN INVENTARIO !!!!!!!!!!!!!!!! AUN NO SE IMPLEMENTA
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
            $factura = Factura::with('productos')->with('cliente')->with('almacen')->find($id);

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
