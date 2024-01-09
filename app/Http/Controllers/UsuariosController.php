<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function login(Request $request){
        $user = User::whereEmail($request->email)->first();

        if(!is_null($user) && Hash::check($request->password, $user->password))
        {
            $user->api_token = Str::random(150);
            $user->save();

            return response()->json([
                'is_error' => false,
                'data' => $user->api_token,
                'message' => 'Bienvenido al sistema'
            ]);
        }
        else{
            return response()->json([
                'is_error' => true,
                'message' => 'Usuario o contraseña incorrecto'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {           

            $user = new User;

            $user->cc = $request->cc;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->rol = $request->rol;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'is_error' => false,
                'message' => 'Registro insertado correctamente'
            ]);
            
        } catch (\Exception $e) {
           throw $e;
        }
        
    }

    public function logout()
    {
        $user = auth()->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'res' => true,
            'message' => 'Adios'
        ]);
    }

    public function showAuth()
    {
        if(auth()->user() != null){
            return response()->json([
                'res' => false,
                'data' => auth()->user(),
            ]);
        }else{
            return response()->json([
                'res' => true,
                'message' => 'Usuario no autenticado'
            ]);
        }
        
    }

    public function getAllUsers()
    {
        try {
            // Obtener todos los usuarios
            $users = User::select('id', 'name', 'email', 'created_at', 'updated_at')->get();

            // Retornar la lista de usuarios en formato JSON
            return response()->json([
                'is_error' => false,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'los usuarios no se obtienen de manera correcta'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function show(usuarios $usuarios)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function edit(usuarios $usuarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, usuarios $usuarios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function destroy(usuarios $usuarios)
    {
        //
    }
}
