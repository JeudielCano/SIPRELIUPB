<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Aqui definimos las reglas para hacer los cambios (las variables que son tomadas en cuenta).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 
                'string', 
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:20'
            ],
            'student_id' => [
                'nullable', 
                'string', 
                'max:20', 
                // Valida que sea única en la tabla 'users', pero ignora al usuario actual
                Rule::unique('users', 'student_id')->ignore($this->user()->id)
            ], // <--- Este campo nos ayudara a modificar las matriculas desde el edit profile
        ];
    }
}
