<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Descripción
            $table->string('inventory_number')->unique()->nullable(); // De tu ERD (Número de Inventario)
            $table->enum('type', ['equipo', 'laboratorio', 'insumo']); // De tu ERD
            $table->integer('total_stock')->default(1); // De tu ERD
            $table->enum('status', ['disponible', 'prestado', 'mantenimiento'])->default('disponible'); // De tu ERD
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
