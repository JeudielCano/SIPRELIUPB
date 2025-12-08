<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_request_id',
        'resource_id',
        'quantity',
        'return_status',      // buen_estado, dañado, perdido
        'return_observations'
    ];

    // RELACIONES

    // Un ítem pertenece a una Solicitud "Padre"
    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }

    // Un ítem hace referencia a un Recurso del inventario
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}