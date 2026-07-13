@extends('layouts.app')
@section('title', 'Pengaturan Cetak')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-xl mx-auto">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">Pengaturan Cetak Formulir</h1>
    <p class="text-sm text-gray-500 mb-6">
        Nama & NRP di sini akan otomatis muncul pada kolom "Juru Bayar Balitbang Kemhan" di semua formulir pinjaman yang dicetak.
    </p>

    <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-gray-600 mb-1">Nama Juru Bayar</label>
            <input type="text" name="nama_juru_bayar" value="{{ old('nama_juru_bayar', $pengaturan->nama_juru_bayar) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">NRP Juru Bayar</label>
            <input type="text" name="nrp_juru_bayar" value="{{ old('nrp_juru_bayar', $pengaturan->nrp_juru_bayar) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
        </div>

        <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-5 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection