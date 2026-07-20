<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_juru_bayar')->nullable();
            $table->string('nrp_juru_bayar')->nullable();
            $table->timestamps();
        });

        DB::table('pengaturan')->insert([
            'nama_juru_bayar' => null,
            'nrp_juru_bayar' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
