<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Confirmation;
use App\Models\Factura;
use App\Models\ProductosConfirmation;
use App\Models\ProductosFactura;
use Illuminate\Http\Request;
use App\Http\Controllers\ArticulosController;
use Illuminate\Support\Facades\DB;

class ConfirmationController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->input('id_usuario')) {
                $confirmation = Confirmation::with('productos')
                    ->with('cliente')
                    ->with('almacen')
                    ->with('usuario')
                    ->where('id_usuario', $request->input('id_usuario'))
                    ->orderBy('created_at', 'desc')
                    ->paginate($request->input('size'));
                
                return response()->json([
                    'is_error' => false,
                    'message' => 'Las confirmaciones se muestran',
                    'data' => $confirmation
                ]);
            } else {
                $confirmation = Confirmation::with('productos')
                    ->with('cliente')
                    ->with('almacen')
                    ->with('usuario')
                    ->orderBy('created_at', 'desc')
                    ->paginate($request->input('size'));
            }

            return response()->json([
                'is_error' => false,
                'message' => 'Las confirmaciones se muestran',
                'data' => $confirmation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Error al mostrar las confirmaciones',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $confirmation = new Confirmation;

            $confirmation->id_usuario = $request->id_usuario;
            $confirmation->referencia = substr($request->reference, 0, 3);
            $confirmation->fecha = $request->date;
            $confirmation->id_cliente = $request->customer['id'];
            $confirmation->id_almacen = $request->store['id'];
            $confirmation->descripcion = $request->description;
            $confirmation->estado = 'pendiente_confirmar';

            $confirmation->save();
            $confirmation->referencia = substr($request->reference, 0, 3) . $confirmation->id;
            $confirmation->save();

            foreach ($request->products as $productData) {
                $product = new ProductosConfirmation;
                $product->id_confirmation = $confirmation->id;
                $product->id_producto = $productData['id'];
                $product->referencia = $productData['referencia'];
                $product->codigo_barras = $productData['codigo_barras'] ?? '';
                $product->nombre = $productData['nombre'];
                $product->cantidad = $productData['cantidad'];
                $product->cantidad_confirmacion = $productData['cantidad_confirmacion'];
                $product->estado = $this->determinarEstadoConfirmacion($productData['cantidad'], $productData['cantidad_confirmacion']);
                $product->save();
            }

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'La confirmación fue registrada correctamente',
                'data' => $confirmation
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'Error al crear la confirmación',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $confirmation = Confirmation::with(['productos.producto', 'cliente', 'almacen', 'usuario'])->findOrFail($id);

            return response()->json([
                'is_error' => false,
                'message' => 'Confirmación encontrada',
                'data' => $confirmation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'No se pudo encontrar la confirmación',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $confirmation = Confirmation::findOrFail($id);

            $confirmation->referencia = $request->reference;
            $confirmation->fecha = $request->date;
            $confirmation->id_cliente = $request->customer['id'];
            $confirmation->id_almacen = $request->store['id'];
            $confirmation->descripcion = $request->description;
            $confirmation->estado = $this->determinarEstadoGeneral($request->products);

            $confirmation->save();

            // Eliminar productos antiguos
            ProductosConfirmation::where('id_confirmation', $confirmation->id)->delete();

            // Crear nuevos registros de productos
            foreach ($request->products as $productData) {
                $product = new ProductosConfirmation;
                $product->id_confirmation = $confirmation->id;
                $product->id_producto = $productData['id_producto'];
                $product->referencia = $productData['referencia'];
                $product->codigo_barras = $productData['codigo_barras'] ?? '';
                $product->nombre = $productData['nombre'];
                $product->cantidad = $productData['cantidad'];
                $product->cantidad_confirmacion = $productData['cantidad_confirmacion'];
                $product->estado = $this->determinarEstadoConfirmacion($productData['cantidad'], $productData['cantidad_confirmacion']);
                $product->save();
            }

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'Confirmación actualizada correctamente',
                'data' => $confirmation
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'Error al actualizar la confirmación',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $confirmation = Confirmation::findOrFail($id);
            
            // Eliminar productos de la confirmación
            ProductosConfirmation::where('id_confirmation', $id)->delete();
            
            // Eliminar la confirmación
            $confirmation->delete();

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'La confirmación se ha eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'Error al eliminar la confirmación',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function check(Request $request)
    {
        try {
            DB::beginTransaction();

            $confirmation = Confirmation::with('productos')->findOrFail($request->id);

            // Verificar si ya está confirmada
            if ($confirmation->estado === 'confirmado') {
                return response()->json([
                    'is_error' => true,
                    'message' => 'La confirmación ya ha sido procesada anteriormente.'
                ]);
            }

            // Actualizar estado de la confirmación
            $confirmation->estado = 'confirmado';
            $confirmation->save();

            // Aquí podrías agregar lógica adicional para actualizar inventario, etc.

            DB::commit();

            return response()->json([
                'is_error' => false,
                'message' => 'La confirmación ha sido procesada correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'Error al procesar la confirmación',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function searchByParams(Request $request)
    {
        try {
            $input = $request->input('input');

            $confirmations = Confirmation::with('productos')
                ->with('cliente')
                ->with('almacen')
                ->with('usuario')
                ->where('referencia', 'like', "%$input%")
                ->orWhereHas('cliente', function($query) use ($input) {
                    $query->where('nombre', 'like', "%$input%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->input('size'));

            return response()->json($confirmations);
        } catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Error al buscar confirmaciones',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Determina el estado de un producto basado en la cantidad solicitada vs confirmada
     */
    private function determinarEstadoConfirmacion($cantidadSolicitada, $cantidadConfirmada)
    {
        if ($cantidadConfirmada == 0) {
            return 'no_confirmado';
        } elseif ($cantidadConfirmada < $cantidadSolicitada) {
            return 'parcial_confirmado';
        } else {
            return 'confirmado';
        }
    }

    /**
     * Determina el estado general de la confirmación basado en los productos
     */
    private function determinarEstadoGeneral($productos)
    {
        $todosConfirmados = true;
        $algunoConfirmado = false;
        $todosRechazados = true;

        foreach ($productos as $producto) {
            $estado = $this->determinarEstadoConfirmacion($producto['cantidad'], $producto['cantidad_confirmacion']);
            
            if ($estado !== 'confirmado') {
                $todosConfirmados = false;
            }
            
            if ($estado !== 'no_confirmado') {
                $todosRechazados = false;
            }
            
            if ($estado !== 'no_confirmado') {
                $algunoConfirmado = true;
            }
        }

        if ($todosConfirmados) {
            return 'confirmado';
        } elseif ($todosRechazados) {
            return 'rechazado';
        } else {
            return 'parcial';
        }
    }
}
