@extends('layouts.app')
@section('title', 'Kelola Data Pegawai')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-semibold text-[#7a1f2b]">Kelola Data Pegawai</h1>
    <a href="{{ route('admin.pegawai.create') }}" class="bg-[#7a1f2b] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#5e1621] transition">
        + Tambah Pegawai
    </a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau NIP/NRP..."
        class="w-full md:w-80 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">NIP/NRP</th>
                <th class="px-4 py-3">Jenis</th>
                <th class="px-4 py-3">Jabatan</th>
                <th class="px-4 py-3">Status Password</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($pegawai as $p)
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="px-4 py-3">{{ $p->nip_nrp }}</td>
                    <td class="px-4 py-3">{{ $p->jenis_personel ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $p->jabatan_satker ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if ($p->must_change_password)
                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Masih default</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs">Sudah diganti</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.pegawai.show', $p) }}"
                                class="px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 text-xs hover:bg-gray-50 transition">
                                Lihat
                            </a>
                            <a href="{{ route('admin.pegawai.edit', $p) }}"
                                class="px-3 py-1.5 rounded-lg bg-[#7a1f2b] text-white text-xs hover:bg-[#5e1621] transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.pegawai.reset-password', $p) }}">
                                @csrf
                                <button type="submit" onclick="return confirm('Reset password {{ $p->name }} ke NIP/NRP?')"
                                    class="px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 text-xs hover:bg-gray-50 transition">
                                    Reset Password
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.pegawai.destroy', $p) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus data {{ $p->name }}? Tindakan ini tidak bisa dibatalkan.')"
                                    class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 text-xs hover:bg-red-50 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada data pegawai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pegawai->links() }}
</div>
@endsection