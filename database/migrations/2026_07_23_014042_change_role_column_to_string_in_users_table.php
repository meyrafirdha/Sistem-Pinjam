<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(50) NOT NULL DEFAULT 'anggota'");
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','anggota') NOT NULL DEFAULT 'anggota'");
    }
};