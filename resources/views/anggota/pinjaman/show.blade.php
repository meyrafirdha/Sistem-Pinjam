@extends('layouts.app')
@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff; }
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-2xl mx-auto">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">Detail Pengajuan Pinjaman</h1>
            <span class="text-xs px-2 py-1 rounded-full border {{ $pinjaman->statusColor() }}">
                {{ $pinjaman->statusLabel() }}
            </span>
        </div>
        <div class="flex gap-2 no-print">
            <button onclick="window.print()"
                class="bg-red-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-red-700 active:scale-95 transition">
                Print
            </button>
        </div>
    </div>

    @if($pinjaman->isPending())
        <div class="mb-6 p-3 rounded-lg bg-yellow-50 text-yellow-700 text-sm border border-yellow-200 no-print">
            Pengajuan kamu masih menunggu persetujuan admin.
        </div>
    @endif

    @if($pinjaman->status === 'ditolak')
        <div class="mb-6 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200 no-print">
            <strong>Pengajuan ini ditolak.</strong>
            @if($pinjaman->catatan_admin)
                Dengan alasan {{ $pinjaman->catatan_admin }} <br>
            @endif
            Silakan ajukan pinjaman baru.
            <a href="{{ route('anggota.pinjaman.create') }}" class="underline font-medium">Ajukan ulang</a>
        </div>
    @endif

    <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
        <div>
            <dt class="text-gray-400">Nama</dt>
            <dd class="text-gray-800">{{ $pinjaman->user->name }}</dd>
        </div>
        <div>
            <dt class="text-gray-400">Pangkat/Gol/NRP</dt>
            <dd class="text-gray-800">{{ trim(($pinjaman->user->pangkat_gol ?? '').' / '.$pinjaman->user->nip_nrp, ' /') }}</dd>
        </div>
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
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Status Angsuran</h2>
        <p class="text-xs text-gray-400 mb-4 no-print">Bagian ini diisi & dikelola oleh admin — kamu hanya bisa memantau.</p>

        <dl class="grid sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-400">Jumlah Angsuran / Bulan</dt>
                <dd class="text-gray-800">
                    @if($pinjaman->jumlah_angsuran)
                        Rp {{ number_format($pinjaman->jumlah_angsuran, 0, ',', '.') }}
                    @else
                        <span class="text-gray-400 italic">Belum ditentukan admin</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-gray-400">Sudah Dibayar</dt>
                <dd class="text-gray-800">{{ $pinjaman->jumlahDibayarKali() }} dari {{ $pinjaman->totalCicilan() }} cicilan</dd>
            </div>
            <div>
                <dt class="text-gray-400">Sisa Angsuran</dt>
                <dd class="text-gray-800">
                    @if($pinjaman->sisaAngsuran() !== null)
                        Rp {{ number_format($pinjaman->sisaAngsuran(), 0, ',', '.') }}
                    @else
                        <span class="text-gray-400 italic">-</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>
</div>
@endsection