<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicilan_angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $table->unsignedInteger('cicilan_ke'); // urutan cicilan: 1, 2, 3, ... N (N = jangka_waktu)
            $table->date('tanggal_bayar')->nullable(); // diisi admin kalau sudah dipotong gajinya
            $table->decimal('jumlah_dipotong', 15, 2)->nullable(); // diisi admin, boleh beda dari jumlah_angsuran standar
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['pinjaman_id', 'cicilan_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicilan_angsuran');
    }
};
