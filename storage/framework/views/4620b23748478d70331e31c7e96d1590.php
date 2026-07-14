
<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-xl font-semibold text-[#7a1f2b]">Profil Saya</h1>
        <span class="px-3 py-1 rounded-full text-xs
            <?php echo e(auth()->user()->isAdmin() ? 'bg-[#7a1f2b] text-white' : 'bg-gray-100 text-gray-600'); ?>">
            <?php echo e(auth()->user()->isAdmin() ? 'Admin' : 'Anggota'); ?>

        </span>
    </div>

    <dl class="space-y-4 text-sm">
        <div class="grid grid-cols-3 gap-4">
            <dt class="text-gray-500">Nama Lengkap</dt>
            <dd class="col-span-2 text-gray-800 font-medium"><?php echo e(auth()->user()->name); ?></dd>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <dt class="text-gray-500">NIP / NRP</dt>
            <dd class="col-span-2 text-gray-800"><?php echo e(auth()->user()->nip_nrp); ?></dd>
        </div>

        <?php if (! (auth()->user()->isAdmin())): ?>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                <dd class="col-span-2 text-gray-800">
                    <?php echo e(auth()->user()->tempat_lahir ?? '-'); ?>, <?php echo e(auth()->user()->tanggal_lahir?->format('d F Y') ?? '-'); ?>

                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Pangkat / Golongan</dt>
                <dd class="col-span-2 text-gray-800"><?php echo e(auth()->user()->pangkat_gol ?? '-'); ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Jenis Personel</dt>
                <dd class="col-span-2 text-gray-800">
                    <?php if(auth()->user()->jenis_personel): ?>
                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs"><?php echo e(auth()->user()->jenis_personel); ?></span>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </dd>
            </div>
            <?php if(auth()->user()->eselon): ?>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Eselon</dt>
                <dd class="col-span-2 text-gray-800"><?php echo e(auth()->user()->eselon); ?></dd>
            </div>
            <?php endif; ?>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Jabatan / Satker</dt>
                <dd class="col-span-2 text-gray-800"><?php echo e(auth()->user()->jabatan_satker ?? '-'); ?></dd>
            </div>
        <?php endif; ?>
    </dl>

    <hr class="my-6 border-gray-100">

    <div class="flex gap-3">
        <a href="<?php echo e(route('profile.edit')); ?>"
            class="inline-block bg-[#7a1f2b] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#5e1621] transition">
            Edit Profil
        </a>
        
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\SistemPinjam\Sistem-Pinjam\resources\views/profile/show.blade.php ENDPATH**/ ?>