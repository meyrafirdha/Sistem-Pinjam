
<?php $__env->startSection('title', 'Detail Pegawai'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-6">Detail Pegawai</h1>

    <dl class="space-y-3 text-sm">
        <div><dt class="text-gray-500">Nama Lengkap</dt><dd class="text-gray-800 font-medium"><?php echo e($pegawai->name); ?></dd></div>
        <div><dt class="text-gray-500">NIP / NRP</dt><dd class="text-gray-800"><?php echo e($pegawai->nip_nrp); ?></dd></div>
        <div><dt class="text-gray-500">Tempat, Tanggal Lahir</dt><dd class="text-gray-800"><?php echo e($pegawai->tempat_lahir ?? '-'); ?>, <?php echo e($pegawai->tanggal_lahir?->format('d F Y') ?? '-'); ?></dd></div>
        <div><dt class="text-gray-500">Pangkat / Golongan</dt><dd class="text-gray-800"><?php echo e($pegawai->pangkat_gol ?? '-'); ?></dd></div>
        <div><dt class="text-gray-500">Jenis Personel</dt><dd class="text-gray-800"><?php echo e($pegawai->jenis_personel ?? '-'); ?></dd></div>
        <div><dt class="text-gray-500">Eselon</dt><dd class="text-gray-800"><?php echo e($pegawai->eselon ?? '-'); ?></dd></div>
        <div><dt class="text-gray-500">Jabatan / Satker</dt><dd class="text-gray-800"><?php echo e($pegawai->jabatan_satker ?? '-'); ?></dd></div>
        <div>
            <dt class="text-gray-500">Status Password</dt>
            <dd>
                <?php if($pegawai->must_change_password): ?>
                    <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Masih default (= NIP/NRP)</span>
                <?php else: ?>
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs">Sudah diganti</span>
                <?php endif; ?>
            </dd>
        </div>
    </dl>

    <p class="text-xs text-gray-400 mt-4 italic">Catatan: password disimpan terenkripsi (hash) sehingga tidak bisa ditampilkan dalam bentuk asli. Gunakan "Reset Password" untuk mengembalikan ke NIP/NRP.</p>

    <div class="flex gap-3 mt-6">
        <a href="<?php echo e(route('admin.pegawai.edit', $pegawai)); ?>"
            class="flex-1 text-center bg-[#7a1f2b] text-white rounded-lg py-2 text-sm hover:bg-[#5e1621] transition">
            Edit
        </a>
        <form method="POST" action="<?php echo e(route('admin.pegawai.reset-password', $pegawai)); ?>" class="flex-1">
            <?php echo csrf_field(); ?>
            <button type="submit" onclick="return confirm('Reset password <?php echo e($pegawai->name); ?> ke NIP/NRP?')"
                class="w-full bg-white border border-gray-300 text-gray-700 rounded-lg py-2 text-sm hover:bg-gray-50 transition">
                Reset Password
            </button>
        </form>
    </div>

    <a href="<?php echo e(route('admin.pegawai.index')); ?>" class="block text-sm text-gray-500 mt-4 hover:underline">← Kembali</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SistemPinjam\Sistem-Pinjam\resources\views/admin/pegawai/show.blade.php ENDPATH**/ ?>