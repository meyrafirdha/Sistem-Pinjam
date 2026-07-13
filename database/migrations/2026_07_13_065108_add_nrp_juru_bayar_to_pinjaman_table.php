<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            if (! Schema::hasColumn('pinjaman', 'nrp_juru_bayar')) {
                $table->string('nrp_juru_bayar')->nullable()->after('nama_juru_bayar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropColumn('nrp_juru_bayar');
        });
    }
};