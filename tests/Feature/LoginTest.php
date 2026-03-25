<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Esto le dice a Pest que ejecute las migraciones en la base de datos en memoria 
// antes de las pruebas, para que las tablas existan.
uses(RefreshDatabase::class);

it('muestra la pantalla de inicio de sesión correctamente', function () {
    $this->get('/login')
         ->assertStatus(200)
         ->assertSee('Iniciar Sesión');
});

it('permite a un usuario iniciar sesión en SIPRELI', function () {
    // 1. PREPARACIÓN: Creamos un usuario de prueba directamente en la memoria
    $user = User::factory()->create([
        'email' => 'profesor@upb.edu.mx',
        'password' => bcrypt('12345678'), // Encriptamos la contraseña como lo hace el sistema real
    ]);

    // 2. ACCIÓN: Simulamos que el usuario llena el formulario de login y lo envía
    $this->post('/login', [
        'email' => 'profesor@upb.edu.mx',
        'password' => '12345678', // Aquí usamos la contraseña sin encriptar, como la escribiría el usuario
    ])
    ->assertRedirect('/dashboard'); // Ajusta '/dashboard' si tu sistema redirige a otra parte (ej. '/inicio')

    // 3. VERIFICACIÓN: Comprobamos que Laravel reconoce a este usuario como autenticado
    $this->assertAuthenticatedAs($user);
});

it('bloquea el acceso si la contraseña es incorrecta', function () {
    // 1. PREPARACIÓN: Creamos el usuario
    User::factory()->create([
        'email' => 'alumno@upb.edu.mx',
        'password' => bcrypt('12345678'),
    ]);

    // 2. ACCIÓN: Enviamos el formulario con una contraseña falsa
    $this->post('/login', [
        'email' => 'alumno@upb.edu.mx',
        'password' => 'clave_falsa',
    ])
    ->assertSessionHasErrors(); // Verificamos que devuelva errores de validación

    // 3. VERIFICACIÓN: Comprobamos que el usuario no logró entrar
    $this->assertGuest();
});