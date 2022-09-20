<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\ProductosCotizacion;
use Illuminate\Http\Request;
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
}
