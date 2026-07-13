<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Data rekening (diisi ulang tiap pengajuan)
            $table->string('no_rekening');
            $table->string('nama_rekening');
            $table->string('nama_bank');
            $table->string('no_hp')->nullable();

            // Data pengajuan
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('jumlah_angsuran', 15, 2);
            $table->string('jangka_waktu');

            // Keterangan hutang bank lain
            $table->boolean('punya_hutang_bank')->default(false);
            $table->string('hutang_bank_nama')->nullable();
            $table->decimal('hutang_bank_angsuran', 15, 2)->nullable();

            // Status pengajuan
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
