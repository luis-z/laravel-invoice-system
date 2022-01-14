<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'nombre' => 'Administrador'
            ],
            [
                'nombre' => 'Cliente'
            ]
        ];

        foreach ($roles as $rol) {
            $nuevo_rol = new Role();
            $nuevo_rol->nombre = $rol['nombre'];
            $nuevo_rol->save();
        }
    }
}
