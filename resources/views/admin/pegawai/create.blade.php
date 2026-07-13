@extends('layouts.app')
@section('title', 'Tambah Pegawai')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-6">Tambah Data Pegawai</h1>

    <form method="POST" action="{{ route('admin.pegawai.store') }}" class="space-y-4">
        @csrf
        @include('admin.pegawai._form')
        <button type="submit" class="w-full bg-[#7a1f2b] text-white rounded-lg py-2 hover:bg-[#5e1621] transition">Simpan</button>
    </form>

    <a href="{{ route('admin.pegawai.index') }}" class="block text-sm text-gray-500 mt-4 hover:underline">← Kembali</a>
</div>
@endsection