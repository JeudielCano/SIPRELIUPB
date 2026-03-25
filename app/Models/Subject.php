<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class Subject extends Model
{
    use HasFactory;
    
    // AÑADE ESTO PARA QUE EL SEEDER FUNCIONE
    protected $fillable = ['name'];

    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class);
    }
}

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique()->nullable()->comment('Clave de la asignatura');
            $table->timestamps();
        });
    }



    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};