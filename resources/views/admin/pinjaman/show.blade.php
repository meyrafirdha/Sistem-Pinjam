@extends('layouts.app')
@section('title', 'Tinjau Pengajuan Pinjaman')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-2xl mx-auto">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">Tinjau Pengajuan Pinjaman</h1>
            <span class="text-xs px-2 py-1 rounded-full border {{ $pinjaman->statusColor() }}">
                {{ $pinjaman->statusLabel() }}
            </span>
        </div>
        <a href="{{ route('anggota.pinjaman.cetak', $pinjaman) }}" target="_blank"
            class="border border-[#7a1f2b] text-[#7a1f2b] rounded-lg px-3 py-2 text-sm hover:bg-[#7a1f2b]/5 active:scale-95 transition">
            Lihat Formulir
        </a>
    </div>

    <p class="text-sm text-gray-500 mb-4">
        Diajukan oleh <strong class="text-gray-700">{{ $pinjaman->user->name }}</strong> ({{ $pinjaman->user->nip_nrp }})
    </p>

    <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
        <div>
            <dt class="text-gray-400">Jabatan/Satker</dt>
            <dd class="text-gray-800">{{ $pinjaman->user->jabatan_satker ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">No. Handphone</dt>
            <dd class="text-gray-800">{{ $pinjaman->no_hp ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Nomor Rekening</dt>
            <dd class="text-gray-800">{{ $pinjaman->no_rekening }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Nama Rekening</dt>
            <dd class="text-gray-800">{{ $pinjaman->nama_rekening }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Nama Bank</dt>
            <dd class="text-gray-800">{{ $pinjaman->nama_bank }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Jumlah Pinjaman</dt>
            <dd class="text-gray-800">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Jumlah Angsuran</dt>
            <dd class="text-gray-800">Rp {{ number_format($pinjaman->jumlah_angsuran, 0, ',', '.') }} / bulan</dd>
        </div>
        <div>
            <dt class="text-gray-400">Jangka Waktu</dt>
            <dd class="text-gray-800">{{ $pinjaman->jangka_waktu }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-gray-400">Hutang pada Bank Lain</dt>
            <dd class="text-gray-800">
                @if($pinjaman->punya_hutang_bank)
                    Ya, di {{ $pinjaman->hutang_bank_nama }} — Rp {{ number_format($pinjaman->hutang_bank_angsuran, 0, ',', '.') }}/bulan
                @else
                    Tidak ada
                @endif
            </dd>
        </div>
    </dl>

    <div class="mt-6 pt-6 border-t border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700 mb-2">Nama Juru Bayar (untuk formulir cetak)</h2>
        <p class="text-xs text-gray-400 mb-3">
            Nama ini akan muncul pada kolom tanda tangan "Juru Bayar Balitbang Kemhan" di formulir cetak pengajuan ini.
        </p>
        <form method="POST" action="{{ route('admin.pinjaman.juru-bayar', $pinjaman) }}" class="flex flex-col sm:flex-row gap-2">
            @csrf
            @method('PUT')
            <input type="text" name="nama_juru_bayar" value="{{ old('nama_juru_bayar', $pinjaman->nama_juru_bayar) }}"
                placeholder="Nama Juru Bayar"
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            <input type="text" name="nrp_juru_bayar" value="{{ old('nrp_juru_bayar', $pinjaman->nrp_juru_bayar) }}"
                placeholder="NRP Juru Bayar"
                class="sm:w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Simpan
            </button>
        </form>
    </div>

    @if(!$pinjaman->isPending())
        <div class="mt-6 pt-6 border-t border-gray-100 text-sm text-gray-500">
            Diproses oleh <strong class="text-gray-700">{{ $pinjaman->processedBy?->name ?? '-' }}</strong>
            pada {{ $pinjaman->processed_at?->translatedFormat('d M Y, H:i') }}
            @if($pinjaman->catatan_admin)
                <div class="mt-2 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                    <strong>Catatan:</strong> {{ $pinjaman->catatan_admin }}
                </div>
            @endif
        </div>
    @else
        <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="flex gap-3 mb-4">
                <form method="POST" action="{{ route('admin.pinjaman.acc', $pinjaman) }}"
                    onsubmit="return confirm('Setujui pengajuan pinjaman ini?')">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-green-700 active:scale-95 transition">
                        ✓ Setujui (ACC)
                    </button>
                </form>

                <button type="button" onclick="document.getElementById('decline-form').classList.toggle('hidden')"
                    class="border border-red-300 text-red-600 rounded-lg px-4 py-2 text-sm hover:bg-red-50 active:scale-95 transition">
                    ✕ Tolak
                </button>
            </div>

            <form id="decline-form" method="POST" action="{{ route('admin.pinjaman.decline', $pinjaman) }}" class="hidden space-y-3">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Alasan Penolakan</label>
                    <textarea name="catatan_admin" rows="3" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30"
                        placeholder="Jelaskan alasan penolakan pengajuan ini...">{{ old('catatan_admin') }}</textarea>
                </div>
                <button type="submit" class="bg-red-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-red-700 active:scale-95 transition">
                    Kirim Penolakan
                </button>
            </form>
        </div>
    @endif
</div>
@endsection