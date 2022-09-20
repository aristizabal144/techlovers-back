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
            $products = Articulo::orderBy('created_at', 'desc')->paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Los productos se muestran',
                'data' => $products
            ]);
        } catch (\Exception $e){
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
            $products->nombre = $request->name;
            $products->valor_entra = $request->entryValue;
            $products->porcentaje_venta = $request->salePercentage;
            $products->valor_venta = $request->saleValue;
            $products->cantidad = $request->amount;
            $products->descripcion = $request->description;
            $products->urlImagen = $request->urlImagen;

            $products->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El producto fue registrado de correcta',
                'data' => $products
            ]);

        } catch(\Exception $e){
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
        } catch(\Exception $e){
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
            $products->nombre = $request->nombre;
            $products->valor_entra = $request->valor_entra;
            $products->porcentaje_venta = $request->porcentaje_venta;
            $products->valor_venta = $request->valor_venta;
            $products->cantidad = $request->cantidad;
            $products->descripcion = $request->descripcion;
            $products->urlImagen = $request->urlImagen;
    
            $products->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El producto se actualizo de manera exitosa',
                'data' => $products
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de actualizar el producto'
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            DB::table('articulos')->delete($id);

            return response()->json([
                'is_error' => false,
                'message' => 'El producto se ha eliminado correctamente',
            ]);
        }catch(\Exception $e){
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

            $products = Articulo::where('nombre','like',"%$input%")
            ->orWhere('referencia','like',"%$input%")
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('size'));

            return response()->json($products);
        } catch (\Exception $e) {
           throw $e;
        }
    }
}
