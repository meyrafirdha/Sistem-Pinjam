
<?php $__env->startSection('title', 'Edit Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-2">Edit Profil</h1>

    <?php if(auth()->user()->isAdmin()): ?>
        <p class="text-xs text-gray-400 mb-6">Sebagai admin, kamu hanya bisa mengubah nama dan NIP/NRP akun. Disarankan mengganti NIP/NRP secara berkala untuk keamanan.</p>
    <?php else: ?>
        <p class="text-xs text-gray-400 mb-6">Data kepegawaian resmi (pangkat, jabatan, dll) hanya bisa diubah oleh admin.</p>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="<?php echo e(old('name', auth()->user()->name)); ?>" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
        </div>

        <?php if(auth()->user()->isAdmin()): ?>
            <div>
                <label class="block text-sm text-gray-600 mb-1">NIP / NRP (maks 18 digit)</label>
                <input type="text" name="nip_nrp" maxlength="18" value="<?php echo e(old('nip_nrp', auth()->user()->nip_nrp)); ?>" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                <p class="text-xs text-gray-400 mt-1">⚠️ Ini juga jadi kredensial login kamu — pastikan diingat/dicatat aman.</p>
            </div>
        <?php else: ?>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', auth()->user()->tempat_lahir)); ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', auth()->user()->tanggal_lahir?->format('Y-m-d'))); ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>
        <?php endif; ?>

        <button type="submit" class="w-full bg-[#7a1f2b] text-white rounded-lg py-2 hover:bg-[#5e1621] transition">
            Simpan Perubahan
        </button>
    </form>

    <a href="<?php echo e(route('profile.show')); ?>" class="block text-sm text-gray-500 mt-4 hover:underline">← Kembali</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SistemPinjam\Sistem-Pinjam\resources\views/profile/edit.blade.php ENDPATH**/ ?>