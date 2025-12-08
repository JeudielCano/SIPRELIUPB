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
}