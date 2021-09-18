<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Usuario";
        $user->email = "user@prueba.com";
        $user->password = Hash::make('secret123');
        $user->rol_id = 1;
        $user->save();

        $user = new User();
        $user->name = "Administrador";
        $user->email = "admin@prueba.com";
        $user->password = Hash::make('secret123');
        $user->rol_id = 2;
        $user->save();
    }
}
