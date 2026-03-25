<?php

use App\Models\User;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Truco para que SQLite no explote con el campo 'career' que no existe en su tabla
    Resource::saving(function ($resource) {
        unset($resource->career);
    });
});

it('permite a un administrador registrar un recurso si el numero de serie UPB es nuevo', function () {
    $admin = User::factory()->create(['role' => 'administrador']);

    $datos = [
        'name' => 'Laptop Nueva',
        'description' => 'Prueba de creación con rastro en consola',
        'type' => 'equipo',
        'career' => 'ITID',
        'inventory_number' => 'UPB-NUEVO-123',
        'total_stock' => 1,
        'status' => 'disponible',
    ];

    // --- ESTO IMPRIMIRÁ LOS DATOS EN TU CONSOLA ---
    echo "\n\n  > INTENTANDO REGISTRAR RECURSO:";
    echo "\n    Nombre: {$datos['name']}";
    echo "\n    Serie:  {$datos['inventory_number']}";
    echo "\n    Carrera: {$datos['career']}\n";

    $response = $this->actingAs($admin)->post('/admin/resources', $datos);
    
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('resources', ['inventory_number' => 'UPB-NUEVO-123']);
});

it('rechaza el registro si el numero de serie UPB ya está en uso', function () {
    $admin = User::factory()->create(['role' => 'administrador']);

    // Registramos el primero
    Resource::create([
        'name' => 'Laptop Original',
        'type' => 'equipo',
        'inventory_number' => 'UPB-DUPLICADO-999',
        'total_stock' => 1,
        'status' => 'disponible',
    ]);

    $datosDuplicados = [
        'name' => 'Laptop Copia',
        'type' => 'equipo',
        'career' => 'IAEV',
        'inventory_number' => 'UPB-DUPLICADO-999', 
        'total_stock' => 1,
        'status' => 'disponible',
    ];

    // --- IMPRIMIMOS EL INTENTO DE DUPLICADO ---
    echo "\n  > PROBANDO DUPLICADO (DEBE FALLAR):";
    echo "\n    Serie Repetida: {$datosDuplicados['inventory_number']}\n";

    $response = $this->actingAs($admin)->post('/admin/resources', $datosDuplicados);

    $response->assertSessionHasErrors('inventory_number');
    $this->assertDatabaseCount('resources', 1);
});