<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // Ej: "ITID"
            $table->string('full_name')->nullable(); // Ej: "Ingeniería en Tecnologías de la Información"
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Insertar las carreras actuales para no perder datos
        DB::table('careers')->insert([
            ['name' => 'ITID', 'full_name' => 'Ingeniería en Tecnologías de la Información Digital', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IAEV', 'full_name' => 'Ingeniería en Aeronáutica y Vehículos', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};