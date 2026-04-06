<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use App\Models\Factura;
use App\Models\ProductosFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Factura::with('productos.producto.manifiesto')
                ->with('cliente')
                ->with('almacen')
                ->with('encargado');

            // Filtro por cliente
            if ($request->filled('id_cliente')) {
                $query->where('id_cliente', $request->input('id_cliente'));
            }

            // Filtro por rango de fechas
            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereDate('fecha', '>=', $request->input('fecha_inicio'))
                      ->whereDate('fecha', '<=', $request->input('fecha_fin'));
            }

            // Filtro por referencia (busqueda)
            if ($request->filled('search')) {
                $query->where('referencia', 'like', '%' . $request->input('search') . '%');
            }

            $factura = $query->orderBy('created_at', 'desc')->paginate($request->input('size'));

            return response()->json([
                'is_error' => false,
                'message' => 'Las facturas se muestran',
                'data' => $factura
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'Las facturas no se muestran',
                'error' => $e
            ]);
        }
    }

    // ESTE METODO SE REPLICA EN LA ACTUALIZACION DE LAS COTIZACIONES
    // LA CREACION DE UNA FACTURA SOLO SE DÁ CUANDO SE PASA DE UNA COTIZACION A FACTURADO!!!!
    public function create(Request $request)
    {
        try {

            DB::beginTransaction();

            $invoice = new Factura;

            $invoice->referencia = $request->reference;
            $invoice->fecha = $request->date;
            $invoice->id_cliente = $request->customer['id'];
            $invoice->id_almacen = $request->store['id'];
            $invoice->descripcion = $request->descripcion;
            $invoice->estado = $request->estado;
            $invoice->total = $request->total;


            $invoice->save();
            // return count($request->products);

            for ($i = 0; $i < count($request->products); $i++) {
                $product = new ProductosFactura;

                $product->id_factura = $invoice->id;
                $product->id_producto = $request->products[$i]['id'];
                $product->referencia = $request->products[$i]['referencia'];
                $product->nombre = $request->products[$i]['nombre'];
                $product->cantidad = $request->products[$i]['cantidad'];
                $product->valor_unidad = $request->products[$i]['valor_unidad'];
                $product->valor_total = $request->products[$i]['valor_total'];

                $product->save();
            }

            DB::commit();


            return response()->json([
                'is_error' => false,
                'message' => 'La factura fue registrado de manera correcta',
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $factura = Factura::with('productos')->with('cliente')->with('almacen')->with('encargado')->find($id);

            return response()->json([
                'is_error' => false,
                'message' => 'La factura seleccionada, se ha encontrado',
                'data' => $factura
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'is_error' => true,
                'message' => 'La factura seleccionada NO, se ha encontrado'
            ]);
        }
    }

    public function pagarTotalidadFactura(Request $request)
    {

        try {

            DB::beginTransaction();

            $factura = Factura::findOrFail($request->id_factura);

            $factura->total_descuento = $factura->total - ($request->valor_descuento + $request->valor_flete + $request->valor_averias + $request->valor_retencion);
            $factura->valor_descuento = $request->valor_descuento;
            $factura->valor_flete = $request->valor_flete;
            $factura->valor_averias = $request->valor_averias;
            $factura->valor_retencion = $request->valor_retencion;
            $factura->faltante_pago = 0;
            $factura->estado = 'pagado';
            $factura->fecha_pago = $request->fecha;


            $factura->save();

            $carbon = new \Carbon\Carbon();

            $abono = new Abonos();

            $abonosYaRealizados = Abonos::where('id_factura', $request->id_factura)->sum('valor');

            $abono->id_factura = $request->id_factura;
            $abono->estado = $request->estado;
            $abono->fecha = $request->fecha;
            $abono->valor = $factura->total_descuento - $abonosYaRealizados;
            $abono->descripcion = 'Ultimo pago';

            $abono->save();

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'message' => 'El pago no se pudo realizar con exito'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product_factura = ProductosFactura::where('id_factura', $id);
            $products_get = $product_factura->get();
            $articulo = new ArticulosController;
            $articulo->handleProductAmount($products_get, 'factura');
            $product_factura->delete();

            Factura::where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'is_error' => false,
                'message' => 'La factura se ha eliminado correctamente',
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'is_error' => true,
                'error' => $e,
                'message' => 'Hubo un error al momento de eliminar la factura'
            ]);
        }
    }


    public function searchByParams(Request $request)
    {
        try {
            $referencia = $request->input('input');
            $facturas = Factura::with('productos')->with('cliente')->with('almacen')->with('encargado')->where('referencia', 'like', "%$referencia%")
                ->orderBy('created_at', 'desc')
                ->paginate($request->input('size'));

            return response()->json($facturas);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    public function searchByDate(Request $request)
    {
        try {
            $desde = $request->input('fecha_inicio');
            $hasta = $request->input('fecha_fin');


            $facturas = Factura::with('cliente')->with('almacen')->with('encargado')->whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->get();


            return response()->json([
                'is_error' => false,
                'message' => 'Se obtienen las facturas de manera exitosa',
                'data' => $facturas,
                'total_facturas' => Factura::whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->orderBy('created_at', 'desc')->sum('total')
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'is_error' => $e,
                'message' => 'Hubo un error obteniendo las facturas'
            ]);
        }
    }


    //----------------------------------------------------------------
    //|                   DASHBOARD FUNCTIONS                        |
    //----------------------------------------------------------------

    public function searchBulletInformation(Request $request)
    {
        try {
            $desde = $request->input('from');
            $hasta = $request->input('to');


            $facturasPagadas = Factura::where('estado', 'pagado')->whereDate('fecha_pago', '>=', $desde)->whereDate('fecha_pago', '<=', $hasta)->sum('total_descuento');
            $cartera = Factura::where('estado', 'pendiente_pago')->sum('faltante_pago');


            return response()->json(['facturas_pagadas' => $facturasPagadas, 'cartera_pendiente' => $cartera]);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    public function searchInvoicePayments(Request $request)
    {
        try {
            $desde = $request->input('from');
            $hasta = $request->input('to');

            $abonosEfectivo = Abonos::with('factura')->whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->where('estado', 'efectivo')->get();
            $abonosEfectivoTotal = Abonos::with('factura')->whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->where('estado', 'efectivo')->sum('valor');
            $abonosTransferencia = Abonos::with('factura')->whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->where('estado', 'transferencia')->get();
            $abonosTransferenciaTotal = Abonos::with('factura')->whereDate('fecha', '>=', $desde)->whereDate('fecha', '<=', $hasta)->where('estado', 'transferencia')->sum('valor');

            return response()->json(['efectivo' => $abonosEfectivo, 'efectivoTotal' => $abonosEfectivoTotal, 'transferencia' => $abonosTransferencia, 'transferenciaTotal' => $abonosTransferenciaTotal]);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    public function downloadFacturaXLSX(Request $request)
    {
        try {

            $productos = $request->input('productos');

            $data = [
                ['REF', 'DESCRIPCION', 'CNTIDAD', 'V UNIDAD', 'TOTAL V. + IVA', '%', 'VALOR % TOTAL', 'VALOR + IVA UNID', 'SUBTOTAL']
            ];

            foreach ($productos as $producto) {
                $porcentaje = 0.4;
                $porcentajeValor = $producto['valor_total'] * $porcentaje;
                $value = [$producto['referencia'], $producto['nombre'], $producto['cantidad'], $producto['valor_unidad'], $producto['valor_total'], $porcentaje, $porcentajeValor, $porcentajeValor / $producto['cantidad'], ($porcentajeValor / $producto['cantidad']) / 1.19];
                array_push($data, $value);
            }


            $csvFileName = 'archivo_csv.csv';
            $csvContent = implode("\n", array_map(function ($row) {
                return implode(',', $row);
            }, $data));

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            ];

            return response($csvContent, 200, $headers);


        }
        catch (\Exception $e) {
            return $e;
            return response()->json([
                'is_error' => true,
                'message' => 'Error al generar el archivo XLSX',
                'error' => $e,
            ]);
        }
    }


}