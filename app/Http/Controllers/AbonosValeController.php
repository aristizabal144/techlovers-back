<?php

namespace App\Http\Controllers;

use App\Models\AbonosVales;
use App\Models\Vale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonosValeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $abonos = AbonosVales::where('id_vale', $request->input('id_vale'))->get();
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

            $abonos = new AbonosVales;

            $abonos->id_vale = $request->id_vale;
            $abonos->estado = $request->estado;
            $abonos->fecha = $request->fecha;
            $abonos->valor = $request->valor_abono;
            $abonos->descripcion = $request->descripcion;

            $abonos->save();

            $vale = Vale::findOrFail($request->id_vale);

            if ($request->valor_abono > $vale->faltante_pago) {
                return response()->json([
                    'is_error' => true,
                    'message' => 'No se puede abonar más de lo que se debe'
                ]);
            } else {
                $vale->faltante_pago -= $request->valor_abono;

                // Si ya se pagó completamente, cambia el estado a "pagado"
                if ($vale->faltante_pago == 0) {
                    $vale->estado = 'pagado';
                }

                $vale->save();
            }

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'El abono fue creado correctamente',
                'data' => $abonos
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'is_error' => true,
                'message' => 'El abono no se pudo crear de manera correcta'
            ]);
        }
    }
}
