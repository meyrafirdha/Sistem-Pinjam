@extends('layouts.app')
@section('title', 'Dashboard Anggota')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 mb-6">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-2">Selamat datang, {{ auth()->user()->name }}</h1>
    <p class="text-sm text-gray-500 mb-6">Ajukan pinjaman baru atau pantau status pengajuan kamu.</p>
    <div class="flex gap-3">
        <a href="{{ route('anggota.pinjaman.create') }}"
            class="inline-block bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
            Ajukan Pinjaman Baru
        </a>
        <a href="{{ route('anggota.pinjaman.index') }}"
            class="inline-block border border-[#7a1f2b] text-[#7a1f2b] rounded-lg px-4 py-2 text-sm hover:bg-[#7a1f2b]/5 active:scale-95 transition">
            Riwayat Pengajuan Saya
        </a>
    </div>
</div>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-[#7a1f2b]">Riwayat Pinjaman Saya</h2>
        <a href="{{ route('anggota.pinjaman.index') }}" class="text-sm text-[#7a1f2b] hover:underline">Lihat semua</a>
    </div>

    @if($pinjaman->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada riwayat pinjaman.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Jumlah Pinjaman</th>
                        <th class="py-2 pr-4">Angsuran/Bulan</th>
                        <th class="py-2 pr-4">Sisa Angsuran</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3 pr-4">Rp {{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}</td>
                            <td class="py-3 pr-4">
                                @if($item->jumlah_angsuran)
                                    Rp {{ number_format($item->jumlah_angsuran, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3 pr-4">
                                @if($item->isDisetujui() && $item->jumlah_angsuran)
                                    Rp {{ number_format($item->sisaAngsuran(), 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3 pr-4">
                                <span class="text-xs px-2 py-1 rounded-full border {{ $item->statusColor() }}">
                                    {{ $item->statusLabel() }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('anggota.pinjaman.show', $item) }}" class="text-[#7a1f2b] hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
