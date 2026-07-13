@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-2">Edit Profil</h1>

    @if (auth()->user()->isAdmin())
        <p class="text-xs text-gray-400 mb-6">Sebagai admin, kamu hanya bisa mengubah nama dan NIP/NRP akun. Disarankan mengganti NIP/NRP secara berkala untuk keamanan.</p>
    @else
        <p class="text-xs text-gray-400 mb-6">Data kepegawaian resmi (pangkat, jabatan, dll) hanya bisa diubah oleh admin.</p>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
        </div>

        @if (auth()->user()->isAdmin())
            <div>
                <label class="block text-sm text-gray-600 mb-1">NIP / NRP (maks 18 digit)</label>
                <input type="text" name="nip_nrp" maxlength="18" value="{{ old('nip_nrp', auth()->user()->nip_nrp) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                <p class="text-xs text-gray-400 mt-1">⚠️ Ini juga jadi kredensial login kamu — pastikan diingat/dicatat aman.</p>
            </div>
        @else
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', auth()->user()->tempat_lahir) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir?->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>
        @endif

        <button type="submit" class="w-full bg-[#7a1f2b] text-white rounded-lg py-2 hover:bg-[#5e1621] transition">
            Simpan Perubahan
        </button>
    </form>

    <a href="{{ route('profile.show') }}" class="block text-sm text-gray-500 mt-4 hover:underline">← Kembali</a>
</div>
@endsection