<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Producto;
use Validator;

class CompraController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required'
        ],[
            'producto_id.required' => 'El producto_id es requerido.'
        ]);

        if ( $validator->fails() ) {
           return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $compra = new Compra();
        $compra->user_id = $request->user()->id;
        $compra->producto_id = $request->producto_id;
 
        if ($compra->save())
            return response()->json([
                'success' => true,
                'data' => $compra->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'No se agrego la compra.'
            ], 500);
    }
}
