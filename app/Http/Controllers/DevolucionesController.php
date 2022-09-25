<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Factura;
use App\Models\Devolucion;
use Illuminate\Support\Facades\DB;

class DevolucionesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $devolucion = Devolucion::orderBy('created_at', 'desc')->paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Las devoluciones se muestran',
                'data' => $devolucion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Las devoluciones no se muestran',
                'error' => $e
            ]);
        }
    }

    public function create(Request $request)
    {
        try {

            DB::beginTransaction();

            $return = new Devolucion;

            $return->id_factura = $request->idInvoice;
            $return->referencia = $request->reference;
            $return->fecha = $request->date;
            $return->id_cliente = $request->customer['id'];
            $return->id_almacen = $request->store['id'];
            $return->total = $request->total;
            $return->descripcion = $request->description;


            $return->save();

            /* Pendiente por revisar para GUARDAR los productos que se traen y modificar una factura ya creada

            for ($i = 0; $i < count($request->products); $i++) {
                $product = new ProductosDevolucion;
                $product->id_cotizacion = $return->id;
                $product->id_producto = $request->products[$i]['id'];
                $product->referencia = $request->products[$i]['referencia'];
                $product->nombre = $request->products[$i]['nombre'];
                $product->cantidad_cotizacion = $request->products[$i]['cantidad_cotizacion'];
                $product->valor_unidad = $request->products[$i]['valor_unidad'];
                $product->valor_total = $request->products[$i]['valor_total'];

                $product->save();
            }
            */
            DB::commit();


            return response()->json([
                'is_error' => false,
                'message' => 'La devolucion fue registrado de manera correcta',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $return = Devolucion::findOrFail($id);

            $return->id_factura = $request->idInvoice;
            $return->referencia = $request->reference;
            $return->fecha = $request->date;
            $return->id_cliente = $request->customer['id'];
            $return->id_almacen = $request->store['id'];
            $return->total = $request->total;
            $return->descripcion = $request->description;

            $return->save();

            return response()->json([
                'is_error' => false,
                'message' => 'La devolución se actualizo de manera exitosa',
                'data' => $return
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de actualizar la devolución'
            ]);
        }
    }

    public function searchByParams(Request $request)
    {
        try {
            $input = $request->input('input');

            $invoices = Factura::with('productos')->where('referencia','like',"%$input%")
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('size'));

            return response()->json($invoices);
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
