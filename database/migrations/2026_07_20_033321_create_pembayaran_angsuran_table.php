<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $table->date('bulan'); // disimpan sebagai tanggal 1 di bulan tsb, misal 2026-07-01
            $table->decimal('jumlah', 15, 2); // jumlah yang dipotong bulan itu (snapshot dari jumlah_angsuran saat ditandai)
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['pinjaman_id', 'bulan']); // 1 pinjaman cuma bisa ditandai 1x per bulan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_angsuran');
    }
};
