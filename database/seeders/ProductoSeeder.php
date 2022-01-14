<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;


class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $productos = [
            [
                'nombre' => 'Producto 1',
                'precio' => '123.45',
                'impuesto' => '5'
            ],
            [
                'nombre' => 'Producto 2',
                'precio' => '45.65',
                'impuesto' => '15'
            ],
            [
                'nombre' => 'Producto 3',
                'precio' => '39.73',
                'impuesto' => '12'
            ],
            [
                'nombre' => 'Producto 4',
                'precio' => '250',
                'impuesto' => '8'
            ],
            [
                'nombre' => 'Producto 5',
                'precio' => '59.35',
                'impuesto' => '10'
            ]
        ];

        foreach ($productos as $producto) {
            $nuevo_producto = new Producto();
            $nuevo_producto->nombre = $producto['nombre'];
            $nuevo_producto->precio = $producto['precio'];
            $nuevo_producto->impuesto = $producto['impuesto'];
            $nuevo_producto->save();
        }
    }
}
