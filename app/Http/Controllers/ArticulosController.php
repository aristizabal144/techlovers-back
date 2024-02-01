<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticulosController extends Controller
{

    public function index(Request $request)
    {
        try {

            $products = new Articulo();

            if ($request->input('state') != 2) {
                $products = $products->where('estado', (int)$request->input('state'));
            }
            $products = $products->where('is_delete', false)->orderBy('created_at', 'desc')->paginate($request->input('size'));

            return response()->json([
                'is_error' => false,
                'message' => 'Los productos se muestran',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Los productos no se muestran'
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $products = new Articulo;

            $products->id_categoria = $request->categoryId;
            $products->referencia = $request->reference;
            $products->codigo_barras = $request->barCode;
            $products->nombre = $request->name;
            $products->valor_entra = $request->entryValue;
            $products->porcentaje_venta = $request->salePercentage;
            $products->valor_venta = $request->saleValue;
            $products->cantidad = $request->amount;
            $products->cantidad_contabilidad = $request->amount_count;
            $products->estado = $request->state  == true ? 1 : 0;
            $products->descripcion = $request->description;
            $products->urlImagen = $request->imageAws;

            $products->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El producto fue registrado de correcta',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'msg_error' => $e,
                'message' => 'El producto no se pudo registrar de manera correcta'
            ]);
        }
    }

    public function show($id)
    {
        try {
            $products = Articulo::find($id);
            return response()->json([
                'is_error' => false,
                'message' => 'El producto seleccionado, se ha encontrado',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'El producto seleccionada NO, se ha encontrado'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $products = Articulo::find($id);

            $products->referencia = $request->referencia;
            $products->codigo_barras = $request->codigo_barras;
            $products->nombre = $request->nombre;
            $products->valor_entra = $request->valor_entra;
            $products->porcentaje_venta = $request->porcentaje_venta;
            $products->valor_venta = $request->valor_venta;
            $products->cantidad = $request->cantidad;
            $products->cantidad_contabilidad = $request->cantidad_contabilidad;
            $products->estado = $request->estado == true ? 1 : 0;
            $products->descripcion = $request->descripcion;
            $products->urlImagen = $request->imageAws;

            $products->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El producto se actualizo de manera exitosa',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => $e
            ]);
        }
    }

    public function destroy($id)
    {
        try {

            $products = Articulo::find($id);

            $products->is_delete = true; 

            $products->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El producto se ha eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error eliminando el producto'
            ]);
        }
    }

    public function searchByParams(Request $request)
    {
        try {
            $input = $request->input('input');

            $products = new Articulo();

            $products = $products->where(function ($q) use ($input) {
                $q->where('nombre', 'like', "%$input%")
                    ->orWhere('referencia', 'like', "%$input%")
                    ->orWhere('codigo_barras', 'like', "%$input%");
            });

            if ($request->input('state') != 2) {
                $products = $products->where('estado', (int)$request->input('state'));
            }



            $products = $products->where('is_delete', false)->orderBy('created_at', 'desc')->paginate($request->input('size'));

            return response()->json($products);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function handleProductAmount($data, $operacion)
    {
        try {

            for ($i = 0; $i < count($data); $i++) {
                $articulo = Articulo::find($data[$i]['id_producto']);
                $articulo->cantidad = $operacion === 'cotizacion' ? ($articulo->cantidad - $data[$i]['cantidad_cotizacion']) : ($articulo->cantidad + $data[$i]['cantidad']);
                $articulo->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function handleProductAmountContabilidad($data, $operacion)
    {
        try {
            for ($i = 0; $i < count($data); $i++) {
                $articulo = Articulo::find($data[$i]['id_producto']);
                $articulo->cantidad_contabilidad = $operacion === 'resta' ? ($articulo->cantidad_contabilidad - $data[$i]['cantidad']) : ($articulo->cantidad_contabilidad + $data[$i]['cantidad']);
                $articulo->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getInventaryTotal(Request $request)
    {
        try {

            $valor_entrada = 0;
            $valor_salida = 0;

            $products = Articulo::where('is_delete', false)->get();

            for ($i = 0; $i < count($products); $i++) {
                $valor_entrada += $products[$i]->cantidad * $products[$i]->valor_entra;
                $valor_salida += $products[$i]->cantidad * $products[$i]->valor_venta;
            }

            return response()->json([
                'is_error' => false,
                'message' => 'Los datos se muestran',
                'valor_entrada' => $valor_entrada,
                'valor_salida' => $valor_salida,
                'total_inventario' => $valor_salida - $valor_entrada 
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Los productos no se muestran'
            ]);
        }
    }
}
