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
        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();

            // Relaciones
            // Si se borra la solicitud padre, se borran los ítems hijos (cascade)
            $table->foreignId('loan_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained();

            // Detalles del ítem
            $table->integer('quantity');
            
            // Estado al devolverlo (null hasta que se devuelva)
            $table->string('return_status')->nullable(); // Ej: 'bueno', 'dañado', 'perdido'
            $table->text('return_observations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_items');
    }
};
