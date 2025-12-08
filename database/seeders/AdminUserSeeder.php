<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca un usuario por 'email'.
        // Si no lo encuentra, lo crea con los datos del segundo array.
        User::firstOrCreate(
            ['email' => 'admin@sipreli.com'],
            [
                'name' => 'Administrador', // O el nombre que prefieras
                'password' => Hash::make('AdminUPB'), // La contraseña que definimos
                'role' => 'administrador',
                'approved_at' => now(),
            ]
        );
    }
}