@extends('layouts.app')
@section('title', 'Cetak Rekap Angsuran')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff; }
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6 no-print">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b]">Rekap Angsuran</h1>
            <p class="text-sm text-gray-400">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pinjaman.index') }}" class="text-sm text-gray-500 hover:underline self-center">Kembali</a>
            <button onclick="window.print()" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Print
            </button>
        </div>
    </div>

    <h2 class="hidden print:block text-lg font-semibold text-[#7a1f2b] mb-1">Rekap Angsuran Pinjaman — USIPA</h2>
    <p class="hidden print:block text-sm text-gray-500 mb-6">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>

    <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm mb-6">
        <div>
            <dt class="text-gray-400">Nama Anggota</dt>
            <dd class="text-gray-800 font-medium">{{ $pinjaman->user->name }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">NIP/NRP</dt>
            <dd class="text-gray-800">{{ $pinjaman->user->nip_nrp }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Jumlah Pinjaman</dt>
            <dd class="text-gray-800">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Angsuran / Bulan</dt>
            <dd class="text-gray-800">
                @if($pinjaman->jumlah_angsuran)
                    Rp {{ number_format($pinjaman->jumlah_angsuran, 0, ',', '.') }}
                @else
                    <span class="text-gray-400 italic">Belum diisi</span>
                @endif
            </dd>
        </div>
        <div>
            <dt class="text-gray-400">Sudah Dibayar</dt>
            <dd class="text-gray-800">{{ $pinjaman->jumlahDibayarKali() }}x potong gaji</dd>
        </div>
        <div>
            <dt class="text-gray-400">Sisa Angsuran</dt>
            <dd class="text-gray-800 font-medium">Rp {{ number_format($pinjaman->sisaAngsuran() ?? 0, 0, ',', '.') }}</dd>
        </div>
    </dl>

    <h3 class="text-sm font-semibold text-gray-700 mb-2">Riwayat Cicilan (Ke-1 s.d. Ke-{{ $pinjaman->totalCicilan() }})</h3>
    @if($pinjaman->cicilanAngsuran->isEmpty())
        <p class="text-gray-400 text-sm italic">Belum ada data cicilan.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-200">
                    <th class="py-2 pr-4">Cicilan</th>
                    <th class="py-2 pr-4">Tanggal Bayar</th>
                    <th class="py-2">Jumlah Dipotong</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pinjaman->cicilanAngsuran as $cicilan)
                    <tr class="border-b border-gray-100">
                        <td class="py-2 pr-4">Ke-{{ $cicilan->cicilan_ke }}</td>
                        <td class="py-2 pr-4">
                            {{ $cicilan->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}
                        </td>
                        <td class="py-2">
                            {{ $cicilan->jumlah_dipotong ? 'Rp '.number_format($cicilan->jumlah_dipotong, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
