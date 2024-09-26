<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonosController extends Controller
{

  public function index(Request $request)
  {
    try {
      $abonos = Abonos::where('id_factura', $request->input('id_factura'))->get();
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

      DB::beginTransaction();

      $abonos = new Abonos;

      $abonos->id_factura = $request->id_factura;
      $abonos->estado = $request->estado;
      $abonos->fecha = $request->fecha;
      $abonos->valor = $request->valor_abono;
      $abonos->descripcion = $request->descripcion;

      $abonos->save();

      $factura = Factura::findOrFail($request->id_factura);

      if ($request->valor_abono > $factura->faltante_pago) {
        return response()->json([
          'is_error' => true,
          'message' => 'No se puede abonar mas de lo que se debe'
        ]);
      } else {
        $factura->faltante_pago = $factura->faltante_pago - $request->valor_abono;

        if ($factura->faltante_pago === 0) {
        }

        $factura->save();
      }

      DB::commit();
    } catch (\Throwable $th) {
      DB::rollback();
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

  public function delete(Request $request)
  {
    try {
      DB::beginTransaction();
      $factura = Factura::findOrFail($request->id_factura);
      $factura->faltante_pago += $request->valor_abono;
      $factura->save();

      DB::table('abonos')->where('id', $request->id)->delete();
      DB::commit();

      return response()->json([
        'is_error' => false,
        'message' => 'El abono fue eliminado correctamente.',
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'is_error' => true,
        'message' => 'Hubo un error eliminando el abono'
      ]);
    }
  }
}
