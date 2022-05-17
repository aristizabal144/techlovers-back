<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\almacenes;
use Illuminate\Http\Request;

class AlmacenesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $stores = almacenes::paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Los almacenes se muestran',
                'data' => $stores
            ]);
        } catch (\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Los almacenes no se muestran'
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $store = new almacenes;

            $store->id = $request->id;
            $store->id_cliente = $request->id_cliente;
            $store->nit = $request->nit;
            $store->nombre = $request->nombre;
            $store->encargado = $request->encargado;
            $store->ciudad = $request->ciudad;
            $store->direccion = $request->direccion;
            $store->telefono = $request->telefono;
            $store->descripcion = $request->descripcion;
    
            $store->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El almacen fue registrado de correcta',
                'data' => $store
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'El almacen no se pudo registrar de manera correcta',
                'error' => $e
            ]);
        }
    }

    public function show($id)
    {
        try {
            $store = almacenes::find($id);
            return response()->json([
                'is_error' => false,
                'message' => 'El almacen seleccionado, se ha encontrado',
                'data' => $store
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'El almacen seleccionada NO, se ha encontrado'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $store = clientes::findOrFail($id);

            $store->id = $request->id;
            $store->id_cliente = $request->id_cliente;
            $store->nit = $request->nit;
            $store->nombre = $request->nombre;
            $store->encargado = $request->encargado;
            $store->ciudad = $request->ciudad;
            $store->direccion = $request->direccion;
            $store->telefono = $request->telefono;
            $store->descripcion = $request->descripcion;
    
            $store->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El almacen se actualizo de manera exitosa',
                'data' => $store
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de actualizar el almacen'
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            DB::table('almacenes')->delete($id);

            return response()->json([
                'is_error' => false,
                'message' => 'El almacen se ha eliminado correctamente',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error eliminando el almacen'
            ]);
        }
    }
}
