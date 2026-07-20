
<?php $__env->startSection('title', 'Rekap Angsuran Bulanan'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff; }
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6 no-print">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b]">Rekap Angsuran — Pinjaman Disetujui</h1>
            <p class="text-sm text-gray-400">Dicetak pada <?php echo e(now()->translatedFormat('d F Y')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.pinjaman.index')); ?>" class="text-sm text-gray-500 hover:underline self-center">Kembali</a>
            <button onclick="window.print()" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                🖨️ Print Rekap
            </button>
        </div>
    </div>

    <h2 class="hidden print:block text-lg font-semibold text-[#7a1f2b] mb-1">Rekap Angsuran Pinjaman — USIPA</h2>
    <p class="hidden print:block text-sm text-gray-500 mb-4">Dicetak pada <?php echo e(now()->translatedFormat('d F Y')); ?></p>

    <?php if($pinjaman->isEmpty()): ?>
        <p class="text-gray-500 text-sm text-center py-8">Belum ada pinjaman yang disetujui.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Anggota</th>
                        <th class="py-2 pr-4">Jumlah Pinjaman</th>
                        <th class="py-2 pr-4">Angsuran/Bulan</th>
                        <th class="py-2 pr-4">Sudah Dibayar</th>
                        <th class="py-2 pr-4">Sisa Angsuran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pinjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4">
                                <div class="text-gray-800"><?php echo e($item->user->name); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($item->user->nip_nrp); ?></div>
                            </td>
                            <td class="py-3 pr-4">Rp <?php echo e(number_format($item->jumlah_pinjaman, 0, ',', '.')); ?></td>
                            <td class="py-3 pr-4">
                                <?php if($item->jumlah_angsuran): ?>
                                    Rp <?php echo e(number_format($item->jumlah_angsuran, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">Belum diisi</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 pr-4"><?php echo e($item->jumlahDibayarKali()); ?>x</td>
                            <td class="py-3 pr-4">Rp <?php echo e(number_format($item->sisaAngsuran() ?? 0, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\meyra\OneDrive\Dokumen\KEMHAN TERBARU\Sistem-Pinjam-main\Sistem-Pinjam-main\resources\views/admin/pinjaman/rekap.blade.php ENDPATH**/ ?>