<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si la columna ya existe por alguna razón, esto evitará que falle,
            // pero lo ideal es que en un migrate:fresh no existan.
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('phone_number')->nullable()->after('password');
                $table->enum('role', ['administrador', 'solicitante'])->default('solicitante')->after('phone_number');
                $table->enum('applicant_type', ['docente', 'alumno', 'externo'])->nullable()->after('role');
                $table->string('student_id')->unique()->nullable()->after('applicant_type');
                $table->timestamp('approved_at')->nullable()->after('student_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'role',
                'applicant_type',
                'student_id',
                'approved_at',
            ]);
        });
    }
};