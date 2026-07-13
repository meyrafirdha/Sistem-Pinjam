@extends('layouts.app')
@section('title', 'Ajukan Pinjaman')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-2xl mx-auto">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">Formulir Pengajuan Pinjaman</h1>
    <p class="text-sm text-gray-400 mb-6">Data Diri: {{ auth()->user()->name }} — {{ auth()->user()->nip_nrp }}</p>

    <form method="POST" action="{{ route('anggota.pinjaman.store') }}" class="space-y-4">
        @csrf

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Nomor Rekening</label>
                <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Nama Rekening</label>
                <input type="text" value="{{ auth()->user()->name }}" readonly disabled
                    class="w-full border border-gray-200 bg-gray-100 text-gray-500 rounded-lg px-3 py-2 cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">Wajib rekening atas nama pribadi.</p>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Nama Bank</label>
                <input type="text" name="nama_bank" value="{{ old('nama_bank') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">No. Handphone</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Jumlah Pinjaman (Rp)</label>
                <input type="number" step="0.01" min="0" name="jumlah_pinjaman" value="{{ old('jumlah_pinjaman') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Jumlah Angsuran per Bulan (Rp)</label>
                <input type="number" step="0.01" min="0" name="jumlah_angsuran" value="{{ old('jumlah_angsuran') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Jangka Waktu Pinjaman</label>
                <input type="text" name="jangka_waktu" value="{{ old('jangka_waktu') }}" placeholder="Contoh: 24 bulan" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <label class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                <input type="checkbox" id="punya_hutang_bank" name="punya_hutang_bank" value="1"
                    {{ old('punya_hutang_bank') ? 'checked' : '' }}
                    onchange="document.getElementById('hutang-detail').classList.toggle('hidden', !this.checked)">
                Saya memiliki hutang pada Bank lain
            </label>

            <div id="hutang-detail" class="grid sm:grid-cols-2 gap-4 {{ old('punya_hutang_bank') ? '' : 'hidden' }}">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nama Bank</label>
                    <input type="text" name="hutang_bank_nama" value="{{ old('hutang_bank_nama') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Angsuran per Bulan (Rp)</label>
                    <input type="number" step="0.01" min="0" name="hutang_bank_angsuran" value="{{ old('hutang_bank_angsuran') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-5 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Kirim Pengajuan
            </button>
            <a href="{{ route('anggota.dashboard') }}" class="text-sm text-gray-500 hover:underline self-center">Batal</a>
        </div>
    </form>
</div>
@endsection