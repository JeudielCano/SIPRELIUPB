<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityType;
class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos updateOrCreate para evitar duplicados si se corre varias veces
        ActivityType::updateOrCreate(['name' => 'Práctica de Laboratorio']);
        ActivityType::updateOrCreate(['name' => 'Proyecto de Asignatura']);
        ActivityType::updateOrCreate(['name' => 'Taller']);
        ActivityType::updateOrCreate(['name' => 'Conferencia']);
        ActivityType::updateOrCreate(['name' => 'Actividad Académica']);
    }
}