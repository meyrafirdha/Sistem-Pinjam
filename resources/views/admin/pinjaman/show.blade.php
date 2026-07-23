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
        <a href="{{ route('anggota.pinjaman.cetak', $pinjaman) }}"
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
            <dd class="text-gray-800">
                @if($pinjaman->jumlah_angsuran)
                    Rp {{ number_format($pinjaman->jumlah_angsuran, 0, ',', '.') }} / bulan
                @else
                    <span class="text-gray-400 italic">Belum diisi</span>
                @endif
            </dd>
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
        <div class="mt-6 pt-6 border-t border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700 mb-2">Arsip Dokumen Hardcopy (Bertanda Tangan)</h2>
            <p class="text-xs text-gray-400 mb-3">
                Upload hasil scan surat yang sudah ditandatangani lengkap (anggota, admin, kabag) sebagai arsip digital.
            </p>

            @if($pinjaman->file_surat_ttd)
                <div class="mb-3 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700 flex justify-between items-center">
                    <span>Dokumen sudah diunggah.</span>
                    <a href="{{ asset('storage/'.$pinjaman->file_surat_ttd) }}" target="_blank" class="underline font-medium">Lihat Dokumen</a>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pinjaman.upload-surat', $pinjaman) }}" enctype="multipart/form-data" class="flex gap-2">
                @csrf
                <input type="file" name="file_surat_ttd" accept=".pdf,.jpg,.jpeg,.png" required
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                    Upload
                </button>
            </form>
        </div>
        <h2 class="text-sm font-semibold text-gray-700 mb-2">Jumlah Angsuran per Bulan</h2>
        <p class="text-xs text-gray-400 mb-3">
            Isi ini setelah dokumen fisik & tanda tangan lengkap. Nilai ini yang dipakai untuk menghitung sisa angsuran.
        </p>
        <form method="POST" action="{{ route('admin.pinjaman.angsuran', $pinjaman) }}" class="flex gap-2">
            @csrf
            @method('PUT')
            <input type="number" step="0.01" min="0" name="jumlah_angsuran" value="{{ old('jumlah_angsuran', $pinjaman->jumlah_angsuran) }}"
                placeholder="Jumlah angsuran per bulan (Rp)"
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
            <button type="submit" class="bg-[#7a1f2b] text-white rounded-lg px-4 py-2 text-sm hover:bg-[#5e1621] active:scale-95 transition">
                Simpan
            </button>
        </form>

        @if($pinjaman->isDisetujui())
            <div class="mt-4 text-sm text-gray-600">
                Sudah dibayar <strong>{{ $pinjaman->jumlahDibayarKali() }}</strong> dari <strong>{{ $pinjaman->totalCicilan() }}</strong> cicilan —
                sisa <strong>Rp {{ number_format($pinjaman->sisaAngsuran() ?? 0, 0, ',', '.') }}</strong>
            </div>

            @if($pinjaman->cicilanAngsuran->isNotEmpty())
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-left text-gray-400 border-b border-gray-100">
                                <th class="py-1 pr-3">Cicilan</th>
                                <th class="py-1 pr-3">Tanggal Bayar</th>
                                <th class="py-1 pr-3">Jumlah Dipotong (Rp)</th>
                                <th class="py-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pinjaman->cicilanAngsuran as $cicilan)
                                @php $formId = 'form-cicilan-'.$cicilan->id; @endphp
                                <tr class="border-b border-gray-50 {{ $cicilan->sudahDibayar() ? 'bg-green-50/40' : '' }}">
                                    <td class="py-1.5 pr-3 font-medium">Ke-{{ $cicilan->cicilan_ke }}</td>
                                    <td class="py-1.5 pr-3">
                                        <input type="date" form="{{ $formId }}" name="tanggal_bayar" value="{{ $cicilan->tanggal_bayar?->format('Y-m-d') }}"
                                            class="border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                                    </td>
                                    <td class="py-1.5 pr-3">
                                        <input type="number" step="0.01" min="0" form="{{ $formId }}" name="jumlah_dipotong"
                                            value="{{ $cicilan->jumlah_dipotong ?? $pinjaman->jumlah_angsuran }}"
                                            class="w-32 border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
                                    </td>
                                    <td class="py-1.5">
                                        <button type="submit" form="{{ $formId }}" class="text-[#7a1f2b] hover:underline">Simpan</button>
                                    </td>
                                </tr>
                                <form id="{{ $formId }}" method="POST" action="{{ route('admin.pinjaman.cicilan.update', [$pinjaman, $cicilan]) }}" class="hidden">
                                    @csrf
                                    @method('PUT')
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
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