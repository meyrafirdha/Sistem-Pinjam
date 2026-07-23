@extends('layouts.app')
@section('title', 'Detail Tagihan Anggota')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('juru-bayar.tagihan.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
    </div>

    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-1">{{ $user->name }}</h1>
    <p class="text-sm text-gray-500 mb-6">{{ $user->nip_nrp }} — {{ $user->jabatan_satker ?: '-' }}</p>

    @if($pinjaman->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Anggota ini tidak memiliki pinjaman aktif.</p>
    @else
        @foreach($pinjaman as $item)
            <div class="mb-8 border border-gray-100 rounded-xl p-5">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-xs text-gray-400">Diajukan {{ $item->created_at->translatedFormat('d M Y') }}</p>
                        <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full border {{ $item->statusColor() }}">
                        {{ $item->statusLabel() }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs">Angsuran/Bulan</p>
                        <p class="text-gray-800">
                            @if($item->jumlah_angsuran)
                                Rp {{ number_format($item->jumlah_angsuran, 0, ',', '.') }}
                            @else
                                <span class="text-gray-400 italic">Belum diisi</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Sudah Dibayar</p>
                        <p class="text-green-700">{{ $item->jumlahDibayarKali() }} dari {{ $item->totalCicilan() }} cicilan</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Sisa Tagihan</p>
                        <p class="text-red-700">Rp {{ number_format($item->sisaAngsuran() ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($item->cicilanAngsuran->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="text-left text-gray-400 border-b border-gray-100">
                                    <th class="py-1 pr-3">Cicilan</th>
                                    <th class="py-1 pr-3">Tanggal Bayar</th>
                                    <th class="py-1 pr-3">Jumlah Dipotong</th>
                                    <th class="py-1">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->cicilanAngsuran as $cicilan)
                                    <tr class="border-b border-gray-50">
                                        <td class="py-1.5 pr-3">Ke-{{ $cicilan->cicilan_ke }}</td>
                                        <td class="py-1.5 pr-3">{{ $cicilan->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}</td>
                                        <td class="py-1.5 pr-3">
                                            @if($cicilan->jumlah_dipotong)
                                                Rp {{ number_format($cicilan->jumlah_dipotong, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-1.5">
                                            @if($cicilan->tanggal_bayar)
                                                <span class="text-green-600">Sudah dibayar</span>
                                            @else
                                                <span class="text-gray-400">Belum dibayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection