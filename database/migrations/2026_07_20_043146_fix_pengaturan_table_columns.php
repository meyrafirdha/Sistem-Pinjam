<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            if (! Schema::hasColumn('pengaturan', 'nama_juru_bayar')) {
                $table->string('nama_juru_bayar')->nullable();
            }
            if (! Schema::hasColumn('pengaturan', 'nrp_juru_bayar')) {
                $table->string('nrp_juru_bayar')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Sengaja dikosongkan
    }
};