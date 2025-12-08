<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type_id',
        'subject_id',
        'approved_by_id',
        'status',      // pendiente, aprobado, rechazado, activo, finalizado
        'pickup_code', // Codigo generado para recojer
        'pickup_at',   // Fecha hora de retiro
        'due_at',      // Fecha hora de entrega
        'return_at',   // Fecha hora real de devolución
        'observations' // Observaciones generales
    ];

    // Casting: Convierte automáticamente estos campos a objetos de fecha (Carbon)
    protected $casts = [
        'pickup_at' => 'datetime',
        'due_at' => 'datetime',
        'return_at' => 'datetime',
    ];

    // RELACIONES

    // Una solicitud pertenece a un Usuario (el solicitante)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una solicitud pertenece a un Tipo de Actividad
    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    // Una solicitud pertenece a una Asignatura
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Una solicitud puede ser aprobada por un Usuario (Admin)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    // Una solicitud tiene MUCHOS ítems (recursos)
    public function items()
    {
        return $this->hasMany(LoanItem::class);
    }
}