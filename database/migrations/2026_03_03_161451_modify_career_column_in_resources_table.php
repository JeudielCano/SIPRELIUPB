<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Primero actualizamos los valores existentes al id correspondiente
        DB::statement("UPDATE resources SET career = '1' WHERE career = 'ITID'");
        DB::statement("UPDATE resources SET career = '2' WHERE career = 'IAEV'");
        DB::statement("UPDATE resources SET career = NULL WHERE career NOT IN ('1', '2')");

        // Cambiamos el tipo de columna de ENUM a unsignedBigInteger (FK)
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('career')->nullable()->change();
        });

        // Agregamos la FK
        Schema::table('resources', function (Blueprint $table) {
            $table->foreign('career')->references('id')->on('careers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['career']);
            $table->enum('career', ['ITID', 'IAEV'])->nullable()->change();
        });
    }
};