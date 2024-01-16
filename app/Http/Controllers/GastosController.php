<?php

namespace App\Http\Controllers;

use App\Models\Gastos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastosController extends Controller {

  public function create(Request $request)
  {
    try {
      $gasto = new Gastos;

      $gasto->fecha = $request->fecha;
      $gasto->valor = $request->valor;
      $gasto->metodo_pago = $request->metodo_pago;
      $gasto->descripcion = $request->descripcion;

      $gasto->save();

      return response()->json([
        'is_error' => false,
        'message' => 'El gasto fue creado correctamente',
        'data' => $gasto
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'El gasto no se pudo crear de manera correcta'
      ]);
    }
  }

  public function index(Request $request)
  {
    try {
      if ($request->input('size') != null) {
        $gastos = Gastos::orderBy('created_at', 'desc')->paginate($request->input('size'));
      } else {
        $gastos = Gastos::all();
      }

      return response()->json([
        'is_error' => false,
        'message' => 'Los gastos se muestran',
        'data' => $gastos
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'Los gastos no se muestran'
      ]);
    }
  }

  public function show($id)
  {
    try {
      $gasto = Gastos::find($id);
      return response()->json([
        'is_error' => false,
        'message' => 'El gasto seleccionado se ha encontrado',
        'data' => $gasto
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'El gasto seleccionado NO, se ha encontrado'
      ]);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $gasto = Gastos::findOrFail($id);

      $gasto->fecha = $request->fecha;
      $gasto->valor = $request->valor;
      $gasto->metodo_pago = $request->metodo_pago;
      $gasto->descripcion = $request->descripcion;

      $gasto->save();

      return response()->json([
        'is_error' => false,
        'message' => 'El gasto se actualizo de manera exitosa',
        'data' => $gasto
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'Hubo un error al momento de actualizar el gasto'
      ]);
    }
  }

  public function delete($id)
  {
    try {
      DB::table('gastos')->delete($id);

      return response()->json([
        'is_error' => false,
        'message' => 'El gasto se ha eliminado',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'is_error' => true,
        'message' => 'Hubo un error eliminando el gasto'
      ]);
    }
  }

  public function searchByDate(Request $request)
    {
        try {
            $desde = $request->input('from');
            $hasta = $request->input('to');
            $tipo_pago = $request->input('type_pay');

            if ($request->input('size') != null) {
              $gastos = Gastos::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->paginate($request->input('size'));
            } else {

              $gastos = Gastos::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta);

              if($tipo_pago != null){
                $gastos = $gastos->where('metodo_pago', $tipo_pago);
              }
              $gastos = $gastos->orderBy('created_at', 'desc')->get();
            }

            return response()->json([
              'is_error' => false,
              'message' => 'Se obtienen los gastos de manera exitosa',
              'data' => $gastos,
              'total_gastos' => Gastos::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->where('metodo_pago', $tipo_pago)->orderBy('created_at', 'desc')->sum('valor')
            ]);
        } catch (\Exception $e) {
          return $e;
          return response()->json([
            'is_error' => $e,
            'message' => 'Hubo un error obteniendo los gasto'
          ]);
        }
    }
}
