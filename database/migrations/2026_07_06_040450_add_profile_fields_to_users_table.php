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
            $table->string('tempat_lahir')->nullable()->after('jabatan_satker');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('jenis_personel', 50)->nullable()->after('tanggal_lahir');
            $table->string('eselon')->nullable()->after('jenis_personel');
        });
    }

    /**
     * Reverse the migrations.
     */
  
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'tanggal_lahir', 'jenis_personel', 'eselon']);
        });
    }
};