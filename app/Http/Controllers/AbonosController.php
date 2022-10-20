<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonosController extends Controller
{

  public function index(Request $request)
  {
    try {
      $abonos = Abonos::all();
      return response()->json([
        'is_error' => false,
        'message' => 'Los abonos se muestran',
        'data' => $abonos
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'is_error' => true,
        'message' => 'Los abonos no se muestran'
      ]);
    }
  }

  public function create(Request $request)
  {
    try {
      $abonos = new Abonos;

      $abonos->id_factura = $request->id_factura;
      $abonos->estado = $request->estado;
      $abonos->fecha = $request->fecha;
      $abonos->valor = $request->valor_abono;
      $abonos->descripcion = $request->descripcion;

      $abonos->save();
    } catch (\Throwable $th) {
      return response()->json([
        'is_error' => $th,
        'message' => 'El registro no se pudo realizar de manera correcta'
      ]);
    }
  }

  public function update(Request $request)
  {
    try {
      $abonos = new Abonos;

      $abonos->id_factura = $request->id_factura;
      $abonos->estado = $request->estado;
      $abonos->fecha = $request->fecha;
      $abonos->valor_abono = $request->valor_abono;
      $abonos->descripcion = $request->descripcion;

      $abonos->save();


      return response()->json([
        'is_error' => false,
        'message' => 'El abono se actualizo de manera exitosa',
        'data' => $abonos
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'is_error' => true,
        'message' => 'Hubo un error al momento de actualizar el abono'
      ]);
    }
  }

  public function delete($id)
  {
    try {
      DB::table('abonos')->delete($id);

      return response()->json([
        'is_error' => false,
        'message' => 'El abono se ha eliminado correctamente',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'Hubo un error eliminando el abono'
      ]);
    }
  }
}
