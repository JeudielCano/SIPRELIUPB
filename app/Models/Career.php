<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = ['name', 'full_name', 'active'];

    public function resources()
    {
        return $this->hasMany(Resource::class, 'career');
    }
}