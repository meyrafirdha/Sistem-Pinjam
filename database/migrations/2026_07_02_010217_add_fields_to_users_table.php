<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip_nrp', 18)->unique()->after('name');
            $table->string('pangkat_gol')->nullable()->after('nip_nrp');
            $table->string('jabatan_satker')->nullable()->after('pangkat_gol');
            $table->boolean('must_change_password')->default(true)->after('password');
            $table->dropColumn(['nip_nik', 'no_hp']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip_nrp', 'pangkat_gol', 'jabatan_satker', 'must_change_password']);
            $table->string('nip_nik', 30)->nullable();
            $table->string('no_hp', 20)->nullable();
        });
    }
};