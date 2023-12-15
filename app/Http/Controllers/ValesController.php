<?php

namespace App\Http\Controllers;

use App\Models\Vale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValesController extends Controller {

  public function create(Request $request)
  {
    try {
      $vales = new Vale;

      $vales->fecha = $request->fecha;
      $vales->valor = $request->valor;
      $vales->id_usuario = $request->id_usuario;
      $vales->estado = $request->descripcion;

      $vales->save();

      return response()->json([
        'is_error' => false,
        'message' => 'El vale fue creado correctamente',
        'data' => $vales
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'El vale no se pudo crear de manera correcta'
      ]);
    }
  }

  public function index(Request $request)
  {
    try {
      if ($request->input('size') != null) {
        $vales = Vale::orderBy('created_at', 'desc')->paginate($request->input('size'));
      } else {
        $vales = Vale::all();
      }

      return response()->json([
        'is_error' => false,
        'message' => 'Los vales se muestran',
        'data' => $vales
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'Los vales no se muestran'
      ]);
    }
  }


  public function delete($id)
  {
    try {
      DB::table('vales')->delete($id);

      return response()->json([
        'is_error' => false,
        'message' => 'El vale se ha eliminado',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'Hubo un error eliminando el vale'
      ]);
    }
  }

  public function searchByDate(Request $request)
    {
        try {
            $desde = $request->input('from');
            $hasta = $request->input('to');

            if ($request->input('size') != null) {
              $vales = Vale::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->paginate($request->input('size'));
            } else {
              $vales = Vale::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->get();
            }

            return response()->json([
              'is_error' => false,
              'message' => 'Se obtienen los vales de manera exitosa',
              'data' => $vales
            ]);
        } catch (\Exception $e) {
          return response()->json([
            'is_error' => $e,
            'message' => 'Hubo un error obteniendo los vales'
          ]);
        }
    }
}
