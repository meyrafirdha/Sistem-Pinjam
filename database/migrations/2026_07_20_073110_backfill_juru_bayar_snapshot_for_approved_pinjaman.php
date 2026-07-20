<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pengaturan = DB::table('pengaturan')->first();

        if (! $pengaturan) {
            return;
        }

        // Kunci (snapshot) nama & NRP Juru Bayar yang berlaku SEKARANG ke semua pengajuan
        // yang statusnya sudah "disetujui" tapi belum punya snapshot sendiri.
        // Supaya kalau nanti admin ganti nama Juru Bayar, pengajuan yang sudah di-ACC
        // sebelumnya TIDAK ikut berubah.
        DB::table('pinjaman')
            ->where('status', 'disetujui')
            ->whereNull('nama_juru_bayar')
            ->update([
                'nama_juru_bayar' => $pengaturan->nama_juru_bayar,
                'nrp_juru_bayar' => $pengaturan->nrp_juru_bayar,
            ]);
    }

    public function down(): void
    {
        // Sengaja dikosongkan — ini migration data satu arah.
    }
};
