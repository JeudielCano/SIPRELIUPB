<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE resources MODIFY COLUMN status ENUM('disponible','prestado','mantenimiento','dado_de_baja') NOT NULL DEFAULT 'disponible'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE resources MODIFY COLUMN status ENUM('disponible','prestado','mantenimiento') NOT NULL DEFAULT 'disponible'");
    }
};