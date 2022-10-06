<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Factura;
use App\Http\Controllers\ArticulosController;
use App\Models\Devolucion;
use App\Models\ProductosDevolucion;
use Illuminate\Support\Facades\DB;

class DevolucionesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $devolucion = Devolucion::with('cliente')->with('almacen')->orderBy('created_at', 'desc')->paginate($request->input('size'));
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
            $return->id_cliente = $request->id_cliente;
            $return->id_almacen = $request->id_almacen;
            $return->referencia = substr($request->reference, 0, 3);
            $return->fecha = $request->date;
            $return->descripcion = $request->description;


            $return->save();
            $return->referencia = substr($request->reference, 0, 3).$return->id;
            $return->save();


            for ($i = 0; $i < count($request->productos); $i++) {
                $product = new ProductosDevolucion;
                $product->id_devolucion = $return->id;
                $product->id_producto = $request->productos[$i]['id_producto'];
                $product->referencia = $request->productos[$i]['referencia'];
                $product->nombre = $request->productos[$i]['nombre'];
                $product->cantidad = $request->productos[$i]['cantidad'];

                $product->save();
            }

            //Se suma de inventario

            $articulo = new ArticulosController;
            $articulo->handleProductReturn($request);

            DB::commit();


            return response()->json([
                'is_error' => false,
                'message' => 'La devolucion fue registrado de manera correcta',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de registrar la devolución'
            ]);
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
