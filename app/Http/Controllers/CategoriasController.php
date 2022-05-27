<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriasController extends Controller
{

    public function index(Request $request)
    {
        try {
            if($request->input('size') != null){
                $categories = Categoria::orderBy('created_at', 'desc')->paginate($request->input('size'));
                
            }else{
                $categories = Categoria::all();
            }

            return response()->json([
                'is_error' => false,
                'message' => 'Las categorias se muestran',
                'data' => $categories
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Las categorias no se muestran'
            ]);
        }        
    }

    public function create(Request $request)
    {
        try {
            $categories = new Categoria;

            $categories->nombre = $request->nombre;
            $categories->descripcion = $request->descripcion;
            $categories->estado = $request->estado;

            $categories->save();

            return response()->json([
                'is_error' => false,
                'message' => 'La categoria fue registrada de correctamente',
                'data' => $categories
            ]);

        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'El registro no se pudo realizar de manera correcta'
            ]);
        }

    }

    public function show($id)
    {
        try {
            $categories = Categoria::find($id);
            return response()->json([
                'is_error' => false,
                'message' => 'La categoria seleccionada, se ha encontrado',
                'data' => $categories
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'La categoria seleccionada NO, se ha encontrado'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $categories = Categoria::findOrFail($id);

            $categories->nombre = $request->nombre;
            $categories->descripcion = $request->descripcion;
            $categories->estado = $request->estado;
    
            $categories->save();

            return response()->json([
                'is_error' => false,
                'message' => 'La categoria se actualizo de manera exitosa',
                'data' => $categories
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de actualizar la categoria'
            ]);
        }

    }

    public function delete($id)
    {
        try{
            DB::table('categorias')->delete($id);

            return response()->json([
                'is_error' => false,
                'message' => 'La categoria se ha eliminado',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error eliminando la categoria'
            ]);
        }
    }

    public function searchByParams(Request $request)
    {
        try {
            $input = $request->input('input');

            $categories = Categoria::where('nombre','like',"%$input%")
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('size'));

            return response()->json($categories);
        } catch (\Exception $e) {
           throw $e;
        }
    }
    
    
}
