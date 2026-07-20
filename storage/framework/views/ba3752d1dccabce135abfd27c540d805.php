<?php $__env->startSection('title', 'Pinjaman Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-[#7a1f2b]">Riwayat Pengajuan Pinjaman</h1>
        <a href="<?php echo e(route('anggota.pinjaman.create')); ?>" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
            + Ajukan Baru
        </a>
    </div>

    <?php if($pinjaman->isEmpty()): ?>
        <p class="text-gray-500 text-sm text-center py-8">Belum ada riwayat pinjaman.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Jumlah Pinjaman</th>
                        <th class="py-2 pr-4">Angsuran/Bulan</th>
                        <th class="py-2 pr-4">Sisa Angsuran</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pinjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4"><?php echo e($item->created_at->translatedFormat('d M Y')); ?></td>
                            <td class="py-3 pr-4">Rp <?php echo e(number_format($item->jumlah_pinjaman, 0, ',', '.')); ?></td>
                            <td class="py-3 pr-4">
                                <?php if($item->jumlah_angsuran): ?>
                                    Rp <?php echo e(number_format($item->jumlah_angsuran, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 pr-4">
                                <?php if($item->isDisetujui() && $item->jumlah_angsuran): ?>
                                    Rp <?php echo e(number_format($item->sisaAngsuran(), 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="text-xs px-2 py-1 rounded-full border <?php echo e($item->statusColor()); ?>">
                                    <?php echo e($item->statusLabel()); ?>

                                </span>
                            </td>
                            <td class="py-3 text-right space-x-3">
                                <a href="<?php echo e(route('anggota.pinjaman.cetak', $item)); ?>" target="_blank" class="text-gray-500 hover:underline">Print</a>
                                <a href="<?php echo e(route('anggota.pinjaman.show', $item)); ?>" class="text-[#7a1f2b] hover:underline">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <?php echo e($pinjaman->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\meyra\OneDrive\Dokumen\KEMHAN TERBARU\Sistem-Pinjam-main\Sistem-Pinjam-main\resources\views/anggota/pinjaman/index.blade.php ENDPATH**/ ?>