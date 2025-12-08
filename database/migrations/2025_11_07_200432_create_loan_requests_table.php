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
        Schema::create('loan_requests', function (Blueprint $table) {
            $table->id();
            
            // Relaciones (Foreign Keys)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El solicitante
            $table->foreignId('activity_type_id')->constrained(); // Tipo de actividad
            $table->foreignId('subject_id')->constrained(); // Asignatura
            
            // El administrador que aprueba (puede ser nulo al principio)
            $table->foreignId('approved_by_id')->nullable()->constrained('users'); 

            // Datos de la solicitud
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado', 'activo', 'finalizado'])->default('pendiente');
            $table->dateTime('pickup_at'); // Fecha retiro
            $table->dateTime('due_at');    // Fecha entrega programada
            $table->dateTime('return_at')->nullable(); // Fecha entrega real
            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_requests');
    }
};
