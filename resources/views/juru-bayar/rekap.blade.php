@extends('layouts.app')
@section('title', 'Rekap Bulanan Pinjaman')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-[#7a1f2b]">Rekap Bulanan Pinjaman</h1>
        <a href="{{ route('juru-bayar.tagihan.index') }}" class="text-sm text-gray-500 hover:underline">← Daftar Anggota</a>
    </div>

    <form method="GET" class="flex gap-3 items-end mb-6">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Filter Bulan</label>
            <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                <option value="">Semua Bulan</option>
                @foreach($bulanTersedia as $b)
                    <option value="{{ $b }}" {{ $bulan === $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y') }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621]">
            Tampilkan
        </button>
        @if($bulan)
            <a href="{{ route('juru-bayar.rekap') }}" class="text-sm text-gray-500 hover:underline px-2 py-2">Reset</a>
        @endif
    </form>

    {{-- Ringkasan Total --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Total Pinjaman Aktif</p>
            <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Total Sudah Dibayar</p>
            <p class="text-lg font-semibold text-green-700">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Total Sisa Tagihan</p>
            <p class="text-lg font-semibold text-red-700">Rp {{ number_format($totalSisa, 0, ',', '.') }}</p>
        </div>
    </div>

    @if($pinjamanPerBulan->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada data pinjaman disetujui pada periode ini.</p>
    @else
        @foreach($pinjamanPerBulan as $bulanKey => $items)
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3 border-b border-gray-200 pb-2">
                    <h3 class="text-md font-semibold text-[#7a1f2b]">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulanKey)->translatedFormat('F Y') }}
                    </h3>
                    <span class="text-xs text-gray-400">{{ $items->count() }} pengajuan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-200">
                                <th class="py-2 pr-4">Tanggal</th>
                                <th class="py-2 pr-4">Anggota</th>
                                <th class="py-2 pr-4">Jumlah Pinjaman</th>
                                <th class="py-2 pr-4">Angsuran/Bulan</th>
                                <th class="py-2 pr-4">Sudah Bayar</th>
                                <th class="py-2 pr-4">Sisa Tagihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 pr-4">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 pr-4">
                                        <div class="text-gray-800">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $item->user->nip_nrp }}</div>
                                    </td>
                                    <td class="py-3 pr-4">Rp {{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}</td>
                                    <td class="py-3 pr-4">
                                        @if($item->jumlah_angsuran)
                                            Rp {{ number_format($item->jumlah_angsuran, 0, ',', '.') }}
                                        @else
                                            <span class="text-gray-400 italic">Belum diisi</span>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4 text-green-700">Rp {{ number_format($item->totalSudahDibayar(), 0, ',', '.') }}</td>
                                    <td class="py-3 pr-4 text-red-700">Rp {{ number_format($item->sisaAngsuran() ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-semibold text-gray-700">
                                <td colspan="2" class="py-2 pr-4">Subtotal {{ \Carbon\Carbon::createFromFormat('Y-m', $bulanKey)->translatedFormat('F Y') }}</td>
                                <td class="py-2 pr-4">Rp {{ number_format($items->sum('jumlah_pinjaman'), 0, ',', '.') }}</td>
                                <td></td>
                                <td class="py-2 pr-4">Rp {{ number_format($items->sum(fn($p) => $p->totalSudahDibayar()), 0, ',', '.') }}</td>
                                <td class="py-2 pr-4">Rp {{ number_format($items->sum(fn($p) => $p->sisaAngsuran() ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection