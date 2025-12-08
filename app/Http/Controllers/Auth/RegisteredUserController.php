<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
        public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            
            // AQUÍ: Eliminamos la regla 'lowercase' que no te gustaba
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            
            // NUEVO: Añadimos validación para los campos personalizados
            'phone_number' => ['nullable', 'string', 'max:20'],
            'applicant_type' => ['required', Rule::in(['alumno', 'docente', 'externo'])],
            
            // NUEVO: 'student_id' es requerido solo si el tipo es 'alumno'
            'student_id' => ['nullable', 'required_if:applicant_type,alumno', 'string', 'max:255', 'unique:users,student_id'],

            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            
            // NUEVO: Añadimos los nuevos campos al crear el usuario
            'phone_number' => $request->phone_number,
            'applicant_type' => $request->applicant_type,
            'student_id' => $request->applicant_type === 'alumno' ? $request->student_id : null,
            
            'password' => Hash::make($request->password),
            // 'role' usará el valor por defecto 'solicitante' de la base de datos
        ]);

        // event(new Registered($user)); // Esta línea puede estar o no, la dejamos comentada

        // Esto ya lo teníamos: redirige al login con el mensaje de aprobación
        return redirect()->route('login')->with('status', '¡Registro exitoso! Tu cuenta está pendiente de aprobación por un administrador.');
    }
}
