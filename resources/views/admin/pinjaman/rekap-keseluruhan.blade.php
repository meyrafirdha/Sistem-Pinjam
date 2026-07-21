@extends('layouts.app')
@section('title', 'Rekap Keseluruhan Pinjaman')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff; }
        table { font-size: 10px; width: 100% !important; }
        th, td { padding: 4px 6px !important; white-space: nowrap; }
        .overflow-x-auto { overflow: visible !important; }
        @page { size: landscape; margin: 12mm; }
    }
</style>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6 no-print">
        <div>
            <h1 class="text-xl font-semibold text-[#7a1f2b]">Rekap Keuangan Pengajuan Pinjaman</h1>
            <p class="text-sm text-gray-400">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pinjaman.index') }}" class="text-sm text-gray-500 hover:underline self-center">Kembali</a>
            <button onclick="window.print()" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Print Rekap
            </button>
        </div>
    </div>

    {{-- Filter Bulan --}}
    <form method="GET" action="{{ route('admin.pinjaman.rekap-keseluruhan') }}" class="flex gap-3 items-end mb-6 no-print">
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
            <a href="{{ route('admin.pinjaman.rekap-keseluruhan') }}" class="text-sm text-gray-500 hover:underline px-2 py-2">Reset</a>
        @endif
    </form>

    <div class="hidden print:block mb-4">
        <h2 class="text-lg font-semibold text-[#7a1f2b] mb-1">
            Rekap Keuangan Pengajuan Pinjaman — USIPA
            @if($bulan)
                ({{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }})
            @endif
        </h2>
        <p class="text-sm text-gray-500">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    {{-- Ringkasan Total --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Total Pengajuan</p>
            <p class="text-lg font-semibold text-gray-800">{{ $pinjaman->count() }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Disetujui</p>
            <p class="text-lg font-semibold text-green-700">{{ $pinjaman->where('status', 'disetujui')->count() }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Ditolak</p>
            <p class="text-lg font-semibold text-red-700">{{ $pinjaman->where('status', 'ditolak')->count() }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Total Sudah Dibayar</p>
            <p class="text-lg font-semibold text-green-700">
                Rp {{ number_format($pinjaman->sum(fn($p) => $p->totalSudahDibayar()), 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400">Total Belum Dibayar</p>
            <p class="text-lg font-semibold text-red-700">
                Rp {{ number_format($totalBelumDibayar, 0, ',', '.') }}
            </p>
        </div>
    </div>

    @if($pinjaman->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada data pengajuan pinjaman pada periode ini.</p>
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
                                <th class="py-2 pr-4">Sisa Angsuran</th>
                                <th class="py-2 pr-4">Status</th>
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
                                    <td class="py-3 pr-4">
                                        @if($item->isDisetujui())
                                            {{ $item->jumlahDibayarKali() }}x
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4">
                                        @if($item->isDisetujui() && $item->jumlah_angsuran)
                                            Rp {{ number_format($item->sisaAngsuran(), 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs px-2 py-1 rounded-full border {{ $item->statusColor() }}">
                                            {{ $item->statusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-semibold text-gray-700">
                                <td colspan="3" class="py-2 pr-4">Subtotal {{ \Carbon\Carbon::createFromFormat('Y-m', $bulanKey)->translatedFormat('F Y') }}</td>
                                <td class="py-2 pr-4">Rp {{ number_format($items->sum('jumlah_pinjaman'), 0, ',', '.') }}</td>
                                <td class="py-2 pr-4">Sudah: Rp {{ number_format($items->sum(fn($p) => $p->totalSudahDibayar()), 0, ',', '.') }}</td>
                                <td class="py-2 pr-4">Sisa: Rp {{ number_format($items->where('status', 'disetujui')->sum(fn($p) => $p->sisaAngsuran() ?? 0), 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection