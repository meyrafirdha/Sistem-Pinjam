<?php $__env->startSection('title', 'Detail Pengajuan Pinjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-2xl mx-auto">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">Detail Pengajuan Pinjaman</h1>
            <span class="text-xs px-2 py-1 rounded-full border <?php echo e($pinjaman->statusColor()); ?>">
                <?php echo e($pinjaman->statusLabel()); ?>

            </span>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('anggota.pinjaman.cetak', $pinjaman)); ?>" target="_blank"
                class="bg-red-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-red-700 active:scale-95 transition">
                Print
            </a>
        </div>
    </div>

    <?php if($pinjaman->isPending()): ?>
        <div class="mb-6 p-3 rounded-lg bg-yellow-50 text-yellow-700 text-sm border border-yellow-200">
            Pengajuan kamu masih menunggu persetujuan admin.
        </div>
    <?php endif; ?>

    <?php if($pinjaman->status === 'ditolak'): ?>
        <div class="mb-6 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
            <strong>Pengajuan ini ditolak.</strong>
            <?php if($pinjaman->catatan_admin): ?>
                Dengan alasan <?php echo e($pinjaman->catatan_admin); ?> <br>
            <?php endif; ?>
            Silakan ajukan pinjaman baru.
            <a href="<?php echo e(route('anggota.pinjaman.create')); ?>" class="underline font-medium">Ajukan ulang</a>
        </div>
    <?php endif; ?>

    <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
        <div>
            <dt class="text-gray-400">Nama</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->user->name); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Pangkat/Gol/NRP</dt>
            <dd class="text-gray-800"><?php echo e(trim(($pinjaman->user->pangkat_gol ?? '').' / '.$pinjaman->user->nip_nrp, ' /')); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Jabatan/Satker</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->user->jabatan_satker ?: '-'); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">No. Handphone</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->no_hp ?: '-'); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Nomor Rekening</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->no_rekening); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Nama Rekening</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->nama_rekening); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Nama Bank</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->nama_bank); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Jumlah Pinjaman</dt>
            <dd class="text-gray-800">Rp <?php echo e(number_format($pinjaman->jumlah_pinjaman, 0, ',', '.')); ?></dd>
        </div>
        <div>
            <dt class="text-gray-400">Jangka Waktu</dt>
            <dd class="text-gray-800"><?php echo e($pinjaman->jangka_waktu); ?></dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-gray-400">Hutang pada Bank Lain</dt>
            <dd class="text-gray-800">
                <?php if($pinjaman->punya_hutang_bank): ?>
                    Ya, di <?php echo e($pinjaman->hutang_bank_nama); ?> — Rp <?php echo e(number_format($pinjaman->hutang_bank_angsuran, 0, ',', '.')); ?>/bulan
                <?php else: ?>
                    Tidak ada
                <?php endif; ?>
            </dd>
        </div>
    </dl>

    <div class="mt-6 pt-6 border-t border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Status Angsuran</h2>
        <p class="text-xs text-gray-400 mb-4">Bagian ini diisi & dikelola oleh admin — kamu hanya bisa memantau.</p>

        <dl class="grid sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-400">Jumlah Angsuran / Bulan</dt>
                <dd class="text-gray-800">
                    <?php if($pinjaman->jumlah_angsuran): ?>
                        Rp <?php echo e(number_format($pinjaman->jumlah_angsuran, 0, ',', '.')); ?>

                    <?php else: ?>
                        <span class="text-gray-400 italic">Belum ditentukan admin</span>
                    <?php endif; ?>
                </dd>
            </div>
            <div>
                <dt class="text-gray-400">Sudah Dibayar</dt>
                <dd class="text-gray-800"><?php echo e($pinjaman->jumlahDibayarKali()); ?> dari <?php echo e($pinjaman->totalCicilan()); ?> cicilan</dd>
            </div>
            <div>
                <dt class="text-gray-400">Sisa Angsuran</dt>
                <dd class="text-gray-800">
                    <?php if($pinjaman->sisaAngsuran() !== null): ?>
                        Rp <?php echo e(number_format($pinjaman->sisaAngsuran(), 0, ',', '.')); ?>

                    <?php else: ?>
                        <span class="text-gray-400 italic">-</span>
                    <?php endif; ?>
                </dd>
            </div>
        </dl>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\meyra\OneDrive\Dokumen\KEMHAN TERBARU\Sistem-Pinjam-main\Sistem-Pinjam-main\resources\views/anggota/pinjaman/show.blade.php ENDPATH**/ ?>