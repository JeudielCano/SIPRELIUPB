<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de ejemplo, puedes cambiarlos por los reales
        Subject::updateOrCreate(['name' => 'Cálculo Diferencial']);
        Subject::updateOrCreate(['name' => 'Programación Orientada a Objetos']);
        Subject::updateOrCreate(['name' => 'Bases de Datos']);
        Subject::updateOrCreate(['name' => 'Redes de Computadoras']);
        Subject::updateOrCreate(['name' => 'Metodología de la Investigación']);
    }
}