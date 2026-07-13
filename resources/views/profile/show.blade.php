@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-xl font-semibold text-[#7a1f2b]">Profil Saya</h1>
        <span class="px-3 py-1 rounded-full text-xs
            {{ auth()->user()->isAdmin() ? 'bg-[#7a1f2b] text-white' : 'bg-gray-100 text-gray-600' }}">
            {{ auth()->user()->isAdmin() ? 'Admin' : 'Anggota' }}
        </span>
    </div>

    <dl class="space-y-4 text-sm">
        <div class="grid grid-cols-3 gap-4">
            <dt class="text-gray-500">Nama Lengkap</dt>
            <dd class="col-span-2 text-gray-800 font-medium">{{ auth()->user()->name }}</dd>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <dt class="text-gray-500">NIP / NRP</dt>
            <dd class="col-span-2 text-gray-800">{{ auth()->user()->nip_nrp }}</dd>
        </div>

        @unless (auth()->user()->isAdmin())
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                <dd class="col-span-2 text-gray-800">
                    {{ auth()->user()->tempat_lahir ?? '-' }}, {{ auth()->user()->tanggal_lahir?->format('d F Y') ?? '-' }}
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Pangkat / Golongan</dt>
                <dd class="col-span-2 text-gray-800">{{ auth()->user()->pangkat_gol ?? '-' }}</dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Jenis Personel</dt>
                <dd class="col-span-2 text-gray-800">
                    @if (auth()->user()->jenis_personel)
                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">{{ auth()->user()->jenis_personel }}</span>
                    @else
                        -
                    @endif
                </dd>
            </div>
            @if (auth()->user()->eselon)
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Eselon</dt>
                <dd class="col-span-2 text-gray-800">{{ auth()->user()->eselon }}</dd>
            </div>
            @endif
            <div class="grid grid-cols-3 gap-4">
                <dt class="text-gray-500">Jabatan / Satker</dt>
                <dd class="col-span-2 text-gray-800">{{ auth()->user()->jabatan_satker ?? '-' }}</dd>
            </div>
        @endunless
    </dl>

    <hr class="my-6 border-gray-100">

    <div class="flex gap-3">
        <a href="{{ route('profile.edit') }}"
            class="inline-block bg-[#7a1f2b] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#5e1621] transition">
            Edit Profil
        </a>
        
    </div>
</div>
@endsection