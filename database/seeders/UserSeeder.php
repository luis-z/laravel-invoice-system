<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = [
            [
                'nombre' => 'Admin',
                'email' => 'admin@email.com',
                'password' => '12345678',
                'rol_id' => 1
            ],
            [
                'nombre' => 'Cliente 1',
                'email' => 'cliente1@email.com',
                'password' => '12345678',
                'rol_id' => 2
            ],
            [
                'nombre' => 'Cliente 2',
                'email' => 'cliente2@email.com',
                'password' => '12345678',
                'rol_id' => 2
            ]
        ];

        foreach ($usuarios as $user) {
            $nuevo_user = new User();
            $nuevo_user->nombre = $user['nombre'];
            $nuevo_user->email = $user['email'];
            $nuevo_user->password = bcrypt($user['password']);
            $nuevo_user->rol_id = $user['rol_id'];
            $nuevo_user->save();
        }
    }
}
