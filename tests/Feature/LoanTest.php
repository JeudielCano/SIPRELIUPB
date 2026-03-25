<?php

use App\Models\User;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\ActivityType;
use App\Models\LoanRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('permite a un usuario solicitar un préstamo de equipo correctamente', function () {
    // 1. PREPARACIÓN: Creamos el entorno necesario
    $user = User::factory()->create();
    
    // Creamos la asignatura y el tipo de actividad (obligatorios por tus FK)
    $subject = Subject::create(['name' => 'Programación Web']);
    $activity = ActivityType::create(['name' => 'Práctica de Laboratorio']);
    
    // Creamos el recurso disponible
    $resource = Resource::create([
        'name' => 'Laptop Dell #5',
        'type' => 'equipo',
        'inventory_number' => 'UPB-LAP-005',
        'total_stock' => 5,
        'status' => 'disponible'
    ]);

    // Simulamos el formato JSON que envía tu Alpine.js en 'selected_items'
    $itemsJson = json_encode([
        [
            'id' => $resource->id,
            'name' => $resource->name,
            'quantity' => 1
        ]
    ]);

    // 2. ACCIÓN: Enviamos la solicitud
    $response = $this->actingAs($user)->post('/loans', [
        'activity_type_id' => $activity->id,
        'subject_id' => $subject->id,
        'pickup_at' => now()->addDay()->format('Y-m-d\TH:i'), // Mañana
        'due_at' => now()->addDay()->addHours(2)->format('Y-m-d\TH:i'), // Mañana + 2 horas
        'observations' => 'Prueba de solicitud automatizada',
        'selected_items' => $itemsJson, // El campo oculto de tu formulario
    ]);

    // 3. VERIFICACIÓN
    // Revisamos que redirija al index de préstamos
    $response->assertRedirect(route('loans.index'));

    // Verificamos que la solicitud exista en la BD
    $this->assertDatabaseHas('loan_requests', [
        'user_id' => $user->id,
        'subject_id' => $subject->id,
        'status' => 'pendiente'
    ]);

    // Verificamos que el ítem se haya guardado en la tabla de detalle
    $this->assertDatabaseHas('loan_items', [
        'resource_id' => $resource->id,
        'quantity' => 1
    ]);
    
    echo "\n  > SOLICITUD REALIZADA CON ÉXITO:";
    echo "\n    Usuario: {$user->name}";
    echo "\n    Recurso: {$resource->name}";
    echo "\n    Estado:  Pendiente\n";
});