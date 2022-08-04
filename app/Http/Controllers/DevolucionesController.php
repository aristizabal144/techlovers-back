<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            return dd("Hola EmA DESDE EL CONTROLADOR");
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Las devoluciones no se muestran',
                'error' => $e
            ]);
        }
    }
}
