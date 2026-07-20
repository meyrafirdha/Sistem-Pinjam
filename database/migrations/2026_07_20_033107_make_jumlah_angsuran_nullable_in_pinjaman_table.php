<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pakai raw SQL (bukan ->change()) supaya tidak butuh package doctrine/dbal
        DB::statement('ALTER TABLE pinjaman MODIFY jumlah_angsuran DECIMAL(15,2) NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE pinjaman MODIFY jumlah_angsuran DECIMAL(15,2) NOT NULL');
    }
};
