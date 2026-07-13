@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-xl font-semibold text-[#7a1f2b] mb-2">Selamat datang, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-500 text-sm">Halaman ini nanti diisi fitur pinjaman.</p>
</div>
@endsection