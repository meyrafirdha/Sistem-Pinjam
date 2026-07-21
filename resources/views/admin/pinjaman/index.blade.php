@extends('layouts.app')
@section('title', 'Kelola Pengajuan Pinjaman')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h1 class="text-xl font-semibold text-[#7a1f2b]">Kelola Pengajuan Pinjaman</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.pinjaman.rekap-keseluruhan') }}" target="_blank"
                class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition self-start">
                Print Rekap
            </a>
            <details class="relative">
                <summary class="list-none cursor-pointer bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                    Edit Nama & NRP Juru Bayar
                </summary>
                <div class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg p-4 z-50">
                    <p class="text-xs text-gray-400 mb-3">
                        Nama & NRP ini berlaku untuk SEMUA formulir pinjaman (semua anggota).
                    </p>
                    <form method="POST" action="{{ route('admin.juru-bayar.update') }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Nama Juru Bayar</label>
                            <input type="text" name="nama_juru_bayar" value="{{ old('nama_juru_bayar', $pengaturan->nama_juru_bayar) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">NRP Juru Bayar</label>
                            <input type="text" name="nrp_juru_bayar" value="{{ old('nrp_juru_bayar', $pengaturan->nrp_juru_bayar) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                        </div>
                        <button type="submit" class="w-full bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                            Simpan
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </div>

    <h2 class="hidden print:block text-lg font-semibold text-[#7a1f2b] mb-1">Kelola Pengajuan Pinjaman — USIPA</h2>
    <p class="hidden print:block text-sm text-gray-500 mb-4">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>

    <div class="flex gap-2 mb-6 text-sm no-print">
        <a href="{{ route('admin.pinjaman.index') }}"
            class="px-3 py-1.5 rounded-full border {{ !$status ? 'bg-[#7a1f2b] text-white border-[#7a1f2b]' : 'text-gray-600 border-gray-300' }}">
            Semua
        </a>
        <a href="{{ route('admin.pinjaman.index', ['status' => 'pending']) }}"
            class="px-3 py-1.5 rounded-full border {{ $status === 'pending' ? 'bg-[#7a1f2b] text-white border-[#7a1f2b]' : 'text-gray-600 border-gray-300' }}">
            Menunggu ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.pinjaman.index', ['status' => 'disetujui']) }}"
            class="px-3 py-1.5 rounded-full border {{ $status === 'disetujui' ? 'bg-[#7a1f2b] text-white border-[#7a1f2b]' : 'text-gray-600 border-gray-300' }}">
            Disetujui ({{ $counts['disetujui'] }})
        </a>
        <a href="{{ route('admin.pinjaman.index', ['status' => 'ditolak']) }}"
            class="px-3 py-1.5 rounded-full border {{ $status === 'ditolak' ? 'bg-[#7a1f2b] text-white border-[#7a1f2b]' : 'text-gray-600 border-gray-300' }}">
            Ditolak ({{ $counts['ditolak'] }})
        </a>
    </div>

    <form method="GET" action="{{ route('admin.pinjaman.index') }}" class="flex flex-wrap gap-3 items-end mb-6 no-print">
    @if($status)
        <input type="hidden" name="status" value="{{ $status }}">
    @endif

    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs text-gray-500 mb-1">Cari nama / NIP-NRP</label>
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari anggota..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
        <input type="date" name="tanggal" value="{{ $tanggal }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
    </div>

    <button type="submit"
        class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#7a1f2b]/90 active:scale-95 transition">
        Cari
    </button>

    @if($search || $tanggal)
        <a href="{{ route('admin.pinjaman.index', ['status' => $status]) }}"
            class="text-sm text-gray-500 hover:underline px-2 py-2">
            Reset
        </a>
    @endif
</form>

    @if($pinjaman->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada pengajuan pinjaman.</p>
    @else
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
                        <th class="py-2 no-print"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $item)
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
                            <td class="py-3 text-right no-print space-x-3">
                                <a href="{{ route('admin.pinjaman.cetak-rekap', $item) }}" target="_blank" class="text-gray-500 hover:underline">Print</a>
                                <a href="{{ route('admin.pinjaman.show', $item) }}" class="text-[#7a1f2b] hover:underline">Tinjau</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 no-print">
            {{ $pinjaman->links() }}
        </div>
    @endif
</div>
@endsection
