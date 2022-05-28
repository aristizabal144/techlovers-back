<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $stores = Almacen::with('cliente')->orderBy('created_at', 'desc')->paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Los almacenes se muestran',
                'data' => $stores
            ]);
        } catch (\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Los almacenes no se muestran',
                'error' => $e
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            // First, we get client
            $client = Cliente::find($request->client_id);

            // Then, we save store
            $store = new Almacen;

            $store->nit = $request->nit;
            $store->nombre = $request->nombre;
            $store->encargado = $request->encargado;
            $store->ciudad = $request->ciudad;
            $store->direccion = $request->direccion;
            $store->telefono = $request->telefono;
            $store->descripcion = $request->descripcion;
    
            $client->almacenes()->save($store);

            return response()->json([
                'is_error' => false,
                'message' => 'El almacen fue registrado de manera correcta',
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
            $store = Almacen::find($id);
            // If i need client owner of this store, we have to able next line
            // $store = $store->cliente;
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
            $store = Almacen::findOrFail($id);

            $store->id = $request->id;
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

    public function searchByParams(Request $request)
    {
        try {
            $input = $request->input('input');

            $stores = Almacen::where('nombre','like',"%$input%")
            ->orWhere('nit','like',"%$input%")
            ->orWhere('encargado','like',"%$input%")
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('size'));

            return response()->json($stores);
        } catch (\Exception $e) {
           throw $e;
        }
    }

    public function search(Request $request){
        
        try {

            $dataToSearch = $request->input('input');
            $size = $request->input('size');

            $response = Almacen::with('cliente')
            ->whereHas('cliente', function ($query) use ($dataToSearch) {
                $query->where('nombre', 'like', '%'.$dataToSearch.'%')
                ->orWhere('identificacion', 'like', '%'.$dataToSearch.'%')
                ->orWhere('telefono_fijo', 'like', '%'.$dataToSearch.'%')
                ->orWhere('celular', 'like', '%'.$dataToSearch.'%');
            })
            ->orWhere('nombre', 'like', '%'.$dataToSearch.'%')
            ->orWhere('nit','like',"%$dataToSearch%")
            ->orWhere('encargado','like',"%$dataToSearch%")
            ->orWhere('telefono','like',"%$dataToSearch%")
            ->orderBy('created_at', 'desc')
            ->paginate($size);
            return response()->json($response);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
