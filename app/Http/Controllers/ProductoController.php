<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Validator;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
 
        return response()->json([
            'success' => true,
            'data' => $productos
        ]);
    }
 
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:productos',
            'precio' => 'required',
            'impuesto' => 'required',
        ],[
            'nombre.required' => 'El nombre es requerido.',
            'nombre.unique' => 'El producto ya se encuentra registrado.',
            'precio.required' => 'El precio es requerido.',
            'impuesto.required' => 'El impuesto es requerido.',
        ]);

        if ( $validator->fails() ) {
           return response()->json(['message' => $validator->errors()->first()], 400);
        }
 
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = bcmul($request->precio, 1, 2);
        $producto->impuesto = bcmul($request->impuesto, 1, 1);
 
        if ($producto->save())
            return response()->json([
                'success' => true,
                'data' => $producto->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'No se agrego el producto.'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        $producto = Producto::where('id', $id)->first();
 
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 400);
        }
 
        $producto->nombre = $request->nombre;
        $producto->precio = bcmul($request->precio, 1, 2);
        $producto->impuesto = bcmul($request->impuesto, 1, 1);
 
        if ($producto->save())
            return response()->json([
                'success' => true,
                'data' => $producto->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'El producto no se pudo actualizar'
            ], 500);
    }
 
    public function destroy($id)
    {
        $producto = Producto::where('id', $id)->first();
 
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 400);
        }
 
        if ($producto->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'El producto no se pudo eliminar'
            ], 500);
        }
    }
}
