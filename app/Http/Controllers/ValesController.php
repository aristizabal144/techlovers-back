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
      $vales->estado = 'pendiente';

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
        $vales = Vale::with('encargado')->orderBy('created_at', 'desc')->paginate($request->input('size'));
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

  public function changeStatus(Request $request)
  {
    try {
      $id = $request->input('id');
      $date = $request->input('fecha');

      $vale = Vale::findOrFail($id);

      $vale->fecha_pago = $date;
      $vale->estado = 'pagado';

      $vale->save();

      return response()->json([
        'is_error' => false,
        'message' => 'El vale fue pagado correctamente',
        'data' => $vale
      ]);
    } catch (\Exception $e) {
      return $e;
      return response()->json([
        'is_error' => true,
        'message' => 'El vale no se pudo pagar de manera correcta'
      ]);
    }
  }


  public function searchByUser(Request $request)
    {
        try {
            $id = $request->input('id');

            if ($request->input('size') != null) {
              $vales = Vale::with('encargado')->where('id_usuario', $id)->orderBy('created_at', 'desc')->paginate($request->input('size'));
            } else {
              $vales = Vale::with('encargado')->where('id_usuario', $id)->orderBy('created_at', 'desc')->get();
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

    public function searchByDate(Request $request)
    {
        try {
            $desde = $request->input('from');
            $hasta = $request->input('to');

            if ($request->input('size') != null) {
              $vale = Vale::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->paginate($request->input('size'));
            } else {

              $vale = Vale::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->get();
            }

            return response()->json([
              'is_error' => false,
              'message' => 'Se obtienen los vales de manera exitosa',
              'vales_generados' => $vale,
              'vales_pagados' => Vale::whereDate('fecha_pago', '>=', $desde)->whereDate('fecha_pago', '<=', $hasta)->where('estado', 'pagado')->orderBy('created_at', 'desc')->get(),
              'total' => Vale::whereDate('fecha_pago', '>=', $desde)->whereDate('fecha_pago', '<=', $hasta)->where('estado', 'pagado')->orderBy('created_at', 'desc')->sum('valor') - Vale::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->sum('valor')
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
