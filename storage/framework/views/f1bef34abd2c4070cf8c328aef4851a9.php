
<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 mb-6">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-2">Dashboard Admin USIPA</h1>
    <p class="text-sm text-gray-500 mb-6">Kelola pengajuan pinjaman anggota di sini.</p>
    <a href="<?php echo e(route('admin.pinjaman.index')); ?>"
        class="inline-block bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
        Lihat Semua Pengajuan Pinjaman
    </a>
</div>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-[#7a1f2b]">Pengajuan Terbaru</h2>
        <a href="<?php echo e(route('admin.pinjaman.index')); ?>" class="text-sm text-[#7a1f2b] hover:underline">Lihat semua</a>
    </div>

    <?php if($pinjaman->isEmpty()): ?>
        <p class="text-gray-500 text-sm text-center py-8">Belum ada riwayat pinjaman.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Anggota</th>
                        <th class="py-2 pr-4">Jumlah Pinjaman</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pinjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4"><?php echo e($item->created_at->translatedFormat('d M Y')); ?></td>
                            <td class="py-3 pr-4"><?php echo e($item->user->name); ?></td>
                            <td class="py-3 pr-4">Rp <?php echo e(number_format($item->jumlah_pinjaman, 0, ',', '.')); ?></td>
                            <td class="py-3 pr-4">
                                <span class="text-xs px-2 py-1 rounded-full border <?php echo e($item->statusColor()); ?>">
                                    <?php echo e($item->statusLabel()); ?>

                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="<?php echo e(route('admin.pinjaman.show', $item)); ?>" class="text-[#7a1f2b] hover:underline">Tinjau</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SistemPinjam\Sistem-Pinjam\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>