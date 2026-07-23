@extends('layouts.app')
@section('title', 'Tagihan Pinjaman Anggota')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-6">Tagihan Pinjaman Anggota</h1>

    <form method="GET" class="flex gap-3 items-end mb-6">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs text-gray-500 mb-1">Cari nama / NIP-NRP</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari anggota..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
        </div>
        <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621]">
            Cari
        </button>
        @if($search)
            <a href="{{ route('juru-bayar.tagihan.index') }}" class="text-sm text-gray-500 hover:underline px-2 py-2">Reset</a>
        @endif
    </form>

    @if($users->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada anggota dengan pinjaman aktif.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Nama</th>
                        <th class="py-2 pr-4">NIP/NRP</th>
                        <th class="py-2 pr-4">Total Pinjaman Aktif</th>
                        <th class="py-2 pr-4">Sudah Dibayar</th>
                        <th class="py-2 pr-4">Sisa Tagihan</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        @php
                            $totalPinjaman = $user->pinjaman->sum('jumlah_pinjaman');
                            $totalDibayar = $user->pinjaman->sum(fn($p) => $p->totalSudahDibayar());
                            $totalSisa = $user->pinjaman->sum(fn($p) => $p->sisaAngsuran() ?? 0);
                        @endphp
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4 text-gray-800">{{ $user->name }}</td>
                            <td class="py-3 pr-4 text-gray-500">{{ $user->nip_nrp }}</td>
                            <td class="py-3 pr-4">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</td>
                            <td class="py-3 pr-4 text-green-700">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                            <td class="py-3 pr-4 text-red-700">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
                            <td class="py-3 text-right">
                                <a href="{{ route('juru-bayar.tagihan.show', $user) }}" class="text-[#7a1f2b] hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection