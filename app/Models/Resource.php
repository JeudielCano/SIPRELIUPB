<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    
    // ¡Importante! Define los campos que se pueden guardar masivamente
    protected $fillable = [
        'name',
        'description',
        'inventory_number',
        'type',
        'career',
        'total_stock',
        'status',
        'image_path',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class, 'career');
    }

    // Subresguardantes asignados a este recurso
    public function guardians()
    {
        return $this->hasMany(ResourceGuardian::class);
    }

    /**
     * Relación: Un recurso pertenece a una carrera.
     */
    public function careerRelation()
    {
        // NOTA: Si tu columna en la base de datos se llama 'career' en lugar de 'career_id', 
        // cambia 'career_id' por 'career' en la línea de abajo.
        return $this->belongsTo(\App\Models\Career::class, 'career_id');
    }
    /**
     * Relación: Obtiene la carrera asignada a este recurso.
     */
    public function assignedCareer()
    {
        // 1er parámetro: El modelo al que nos conectamos (Career)
        // 2do parámetro: El nombre de la columna en TU tabla resources ('career')
        // 3er parámetro: El nombre de la columna en la tabla careers ('id')
        return $this->belongsTo(\App\Models\Career::class, 'career', 'id');
    }
}

