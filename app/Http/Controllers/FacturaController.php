<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Factura;
use App\Models\User;
use Validator;

class FacturaController extends Controller
{

    public function listar_facturas (Request $request)
    {
        $facturas = Factura::all();

        foreach ($facturas as $factura) {
            $factura->cliente = User::where('id', $factura->user_id)->value('nombre');
        }

        return response()->json([
            'success' => true,
            'data' => $facturas->toArray()
        ]);
    }

    public function detalle_factura (Request $request)
    {
        $compras = Compra::where('factura_id', $request->factura_id)->get();

        foreach ($compras as $compra) {
            
            $compra->nombre_cliente = User::where('id', $compra->user_id)->value('nombre');

            $producto = Producto::where('id', $compra->producto_id)->first();

            $compra->nombre_producto = $producto->nombre;
            $compra->precio_producto = $producto->precio;
            $compra->impuesto_producto = $producto->impuesto;
        }

        return response()->json([
            'success' => true,
            'data' => $compras->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $users = User::where('rol_id', 2)->get();

        foreach ($users as $user) {
            $conditions = ['user_id' => $user->id, 'facturada' => false];
    
            $compras_pendientes = Compra::where($conditions)->get();
    
            if (count($compras_pendientes) <= 0 ) {
                continue;
            }
    
            $factura = new Factura();
            $factura->user_id = $user->id;
            
            if ($factura->save()) {
    
                $total_productos = count($compras_pendientes);
                $costo_total = 0;
                $impuesto_total = 0;
    
                foreach ($compras_pendientes as $compra) {
                    $compra_actualizada = Compra::where('id', $compra->id)->first();
                    $compra_actualizada->factura_id = $factura->id;
                    $compra_actualizada->facturada = true;
                    $compra_actualizada->save();
    
                    $costo_compra = Producto::where('id', $compra->producto_id)->value('precio');
    
                    $costo_total = bcadd($costo_total, $costo_compra, 2);
    
                    $impuesto = bcdiv(Producto::where('id', $compra->producto_id)->value('impuesto'), 100, 2);
                    $impuesto_base = bcmul($impuesto, $costo_compra, 2);
                    $impuesto_total = bcadd($impuesto_total, $impuesto_base, 2);
                }
    
                $factura->costo_total = $costo_total;
                $factura->impuesto_total = $impuesto_total;
                $factura->cantidad_productos = $total_productos;
                $factura->save();
                
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se agrego la factura.'
                ], 500);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }
}
