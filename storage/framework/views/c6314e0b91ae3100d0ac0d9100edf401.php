<?php $__env->startSection('title', 'Tinjau Pengajuan Pinjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-2xl mx-auto">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">Tinjau Pengajuan Pinjaman</h1>
            <span class="text-xs px-2 py-1 rounded-full border <?php echo e($pinjaman->statusColor()); ?>">
                <?php echo e($pinjaman->statusLabel()); ?>

            </span>
        </div>
        <a href="<?php echo e(route('anggota.pinjaman.cetak', $pinjaman)); ?>" target="_blank"
            class="border border-[#7a1f2b] text-[#7a1f2b] rounded-lg px-3 py-2 text-sm hover:bg-[#7a1f2b]/5 active:scale-95 transition">
            Lihat Formulir
        </a>
    </div>

    <p class="text-sm text-gray-500 mb-4">
        Diajukan oleh <strong class="text-gray-700"><?php echo e($pinjaman->user->name); ?></strong> (<?php echo e($pinjaman->user->nip_nrp); ?>)
    </p>

    <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
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
            <dt class="text-gray-400">Jumlah Angsuran</dt>
            <dd class="text-gray-800">Rp <?php echo e(number_format($pinjaman->jumlah_angsuran, 0, ',', '.')); ?> / bulan</dd>
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
        <h2 class="text-sm font-semibold text-gray-700 mb-2">Nama Juru Bayar (untuk formulir cetak)</h2>
        <p class="text-xs text-gray-400 mb-3">
            Nama ini akan muncul pada kolom tanda tangan "Juru Bayar Balitbang Kemhan" di formulir cetak pengajuan ini.
        </p>
        <form method="POST" action="<?php echo e(route('admin.pinjaman.juru-bayar', $pinjaman)); ?>" class="flex flex-col sm:flex-row gap-2">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="text" name="nama_juru_bayar" value="<?php echo e(old('nama_juru_bayar', $pinjaman->nama_juru_bayar)); ?>"
                placeholder="Nama Juru Bayar"
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            <input type="text" name="nrp_juru_bayar" value="<?php echo e(old('nrp_juru_bayar', $pinjaman->nrp_juru_bayar)); ?>"
                placeholder="NRP Juru Bayar"
                class="sm:w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Simpan
            </button>
        </form>
    </div>

    <?php if(!$pinjaman->isPending()): ?>
        <div class="mt-6 pt-6 border-t border-gray-100 text-sm text-gray-500">
            Diproses oleh <strong class="text-gray-700"><?php echo e($pinjaman->processedBy?->name ?? '-'); ?></strong>
            pada <?php echo e($pinjaman->processed_at?->translatedFormat('d M Y, H:i')); ?>

            <?php if($pinjaman->catatan_admin): ?>
                <div class="mt-2 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                    <strong>Catatan:</strong> <?php echo e($pinjaman->catatan_admin); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="flex gap-3 mb-4">
                <form method="POST" action="<?php echo e(route('admin.pinjaman.acc', $pinjaman)); ?>"
                    onsubmit="return confirm('Setujui pengajuan pinjaman ini?')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-green-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-green-700 active:scale-95 transition">
                        ✓ Setujui (ACC)
                    </button>
                </form>

                <button type="button" onclick="document.getElementById('decline-form').classList.toggle('hidden')"
                    class="border border-red-300 text-red-600 rounded-lg px-4 py-2 text-sm hover:bg-red-50 active:scale-95 transition">
                    ✕ Tolak
                </button>
            </div>

            <form id="decline-form" method="POST" action="<?php echo e(route('admin.pinjaman.decline', $pinjaman)); ?>" class="hidden space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Alasan Penolakan</label>
                    <textarea name="catatan_admin" rows="3" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30"
                        placeholder="Jelaskan alasan penolakan pengajuan ini..."><?php echo e(old('catatan_admin')); ?></textarea>
                </div>
                <button type="submit" class="bg-red-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-red-700 active:scale-95 transition">
                    Kirim Penolakan
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\meyra\OneDrive\Dokumen\KEMHAN TERBARU\Sistem-Pinjam-main\Sistem-Pinjam-main\resources\views/admin/pinjaman/show.blade.php ENDPATH**/ ?>