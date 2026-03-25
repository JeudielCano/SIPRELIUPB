<?php

use App\Models\User;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\ActivityType;
use App\Models\LoanRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('muestra la lista de prestamos propios y oculta los ajenos', function () {
    // 1. Preparación de Usuarios
    $yo = User::factory()->create(['name' => 'Vale User']);
    $otro = User::factory()->create(['name' => 'Intruso']);

    // 2. Datos necesarios para las llaves foráneas (Usaremos nombres únicos)
    $miMateria = Subject::create(['name' => 'Programación Avanzada']);
    $materiaAjena = Subject::create(['name' => 'Cocina Internacional']);
    
    $activity = ActivityType::create(['name' => 'Práctica']);

    // 3. Creamos un préstamo MÍO
    $miLoan = LoanRequest::create([
        'user_id' => $yo->id,
        'activity_type_id' => $activity->id,
        'subject_id' => $miMateria->id, // Mi materia
        'status' => 'pendiente',
        'pickup_at' => now()->addDay(),
        'due_at' => now()->addDay()->addHour(),
        'observations' => 'Mi observación secreta' 
    ]);

    // 4. Creamos un préstamo de OTRA PERSONA
    $otroLoan = LoanRequest::create([
        'user_id' => $otro->id,
        'activity_type_id' => $activity->id,
        'subject_id' => $materiaAjena->id, // Materia ajena
        'status' => 'aprobado',
        'pickup_at' => now()->addDay(),
        'due_at' => now()->addDay()->addHour(),
        'observations' => 'Observación ajena'
    ]);

    // ACCIÓN
    $response = $this->actingAs($yo)->get('/loans');

    // VERIFICACIÓN
    $response->assertStatus(200);
    
    // 1. Debo ver mi folio
    $response->assertSee("#" . $miLoan->id);
    
    // 2. Debo ver el nombre de MI asignatura (esto sí se imprime en tu Blade)
    $response->assertSee('Programación Avanzada');

    // 3. NO debo ver el folio del otro ni SU asignatura
    $response->assertDontSee("#" . $otroLoan->id);
    $response->assertDontSee('Cocina Internacional');

    echo "\n  > VISTA INDEX: Privacidad verificada mediante Asignaturas.";
});

it('muestra el código de retiro solo cuando la solicitud está aprobada', function () {
    $yo = User::factory()->create();
    $subject = Subject::create(['name' => 'Base de Datos']);
    $activity = ActivityType::create(['name' => 'Examen']);

    // Creamos un préstamo APROBADO con código
    $loanAprobado = LoanRequest::create([
        'user_id' => $yo->id,
        'activity_type_id' => $activity->id,
        'subject_id' => $subject->id,
        'status' => 'aprobado',
        'pickup_code' => 'XYZ123',
        'pickup_at' => now()->addDay(),
        'due_at' => now()->addDay()->addHour(),
    ]);

    $response = $this->actingAs($yo)->get('/loans');

    // Verificamos que el código aparezca en el HTML
    $response->assertSee('XYZ123');
    $response->assertSee('Tu Código');

    echo "\n  > VISTA INDEX: Código de retiro visible en estado Aprobado.\n";
});