<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        //Crear el empleado que sera superadmin. por defecto lo asignamos al id 1 = DIRIS SEDE ADMINISTRATIVA
        $empleado = Empleado::first();
        $superadmin = $empleado->user()->create([
            // 'id' => $sid,
            'username' => 'superadmin',
            'nombre_completo' => 'Super Administrador',
            'cargo' => 'Super Administrador del Sistema',
            'email' => 'superadmin@superadmin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superadmin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $superadmin->assignRole(config('filament-shield.super_admin.name'));
    }
}

