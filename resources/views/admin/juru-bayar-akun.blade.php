@extends('layouts.app')
@section('title', 'Kelola Akun Juru Bayar')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-lg mx-auto">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-6">Kelola Akun Juru Bayar</h1>

    @if(!$akun)
        <p class="text-gray-500 text-sm">Akun Juru Bayar belum dibuat.</p>
    @else
        <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100 text-sm">
            <p class="text-gray-400 text-xs mb-1">Username Login (NIP/NRP) Saat Ini</p>
            <p class="text-gray-800 font-medium">{{ $akun->nip_nrp }}</p>
        </div>

        <p class="text-xs text-gray-400 mb-4">
            Ganti username & password ini setiap kali pejabat Juru Bayar berganti orang, supaya yang lama tidak bisa login lagi.
        </p>

        <form method="POST" action="{{ route('admin.juru-bayar-akun.password') }}" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm text-gray-600 mb-1">Username (NIP/NRP) Baru</label>
                <input type="text" name="nip_nrp" value="{{ $akun->nip_nrp }}" required maxlength="18"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                <p class="text-xs text-gray-400 mt-1">Ganti ke kombinasi acak yang tidak mudah ditebak.</p>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                <input type="text" name="password" required minlength="6"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30"
                    placeholder="Masukkan password baru">
            </div>
            <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Simpan Perubahan
            </button>
        </form>
    @endif
</div>
@endsection