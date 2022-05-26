<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{

    public function index(Request $request)
    {
        try {
            $clients = Cliente::with('almacenes')->orderBy('created_at', 'desc')->paginate($request->input('size'));
            return response()->json([
                'is_error' => false,
                'message' => 'Los clientes se muestran',
                'data' => $clients
            ]);
        } catch (\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Los clientes no se muestran',
                'error' => $e
            ]);
        }
    }

    public function create(Request $request)
    {
        try {

            DB::beginTransaction();

            $client = new Cliente;

            $client->identificacion = $request->client['id'];
            $client->nombre = $request->client['name'];
            $client->telefono_fijo = $request->client['phoneNumber'];
            $client->celular = $request->client['cellNumber'];
            $client->correo = $request->client['email'];
            $client->descripcion = $request->client['description'];

            $client->save();

            
            for ($i=0; $i < count($request->stores); $i++) {
                $store = new Almacen;
                $store->id_cliente = $client->id;
                $store->nit = $request->stores[$i]['nit'];
                $store->nombre = $request->stores[$i]['nombre'];
                $store->encargado = $request->stores[$i]['encargado'];
                $store->ciudad = $request->stores[$i]['ciudad'];
                $store->direccion = $request->stores[$i]['direccion'];
                $store->telefono = $request->stores[$i]['telefono'];
                $store->descripcion = $request->stores[$i]['descripcion'];
                $store->save(); 
            }

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'El cliente fue registrado de manera correcta',
                'data' => $client
            ]);
        } catch(\Exception $e){
            DB::rollback();
            /* return response()->json([
                'is_error' => true,
                'message' => 'El cliente no se pudo registrar de manera correcta',
                'error' => $e
            ]); */

            throw $e;
        }
    }

    public function show($id)
    {
        try {
            // This will return client with stores info
            $client = Cliente::find($id);
            return response()->json([
                'is_error' => false,
                'message' => 'El cliente seleccionado, se ha encontrado',
                'data' => $client
            ]);
            dd($client);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'El cliente seleccionada NO, se ha encontrado'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = Cliente::findOrFail($id);

            $client->identificacion = $request->identificacion;
            $client->nombre = $request->nombre;
            $client->telefono_fijo = $request->telefono_fijo;
            $client->celular = $request->celular;
            $client->correo = $request->correo;
            $client->descripcion = $request->descripcion;
    
            $client->save();

            return response()->json([
                'is_error' => false,
                'message' => 'El cliente se actualizo de manera exitosa',
                'data' => $client
            ]);
        } catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error al momento de actualizar el cliente'
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            DB::table('clientes')->delete($id);

            return response()->json([
                'is_error' => false,
                'message' => 'El cliente se ha eliminado correctamente',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'is_error' => true,
                'message' => 'Hubo un error eliminando el cliente'
            ]);
        }
    }
}
