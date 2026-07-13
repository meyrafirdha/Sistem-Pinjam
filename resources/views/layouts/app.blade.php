<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Simpan Pinjam Anggota')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white border-b border-gray-200">
        {{-- BARIS 1: logo --}}
        <div class="w-full px-8 py-2 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/koperasi logo.png') }}" alt="Logo Koperasi" class="h-20 w-20 object-contain">
                <div class="leading-tight">
                    <div class="font-semibold text-[#7a1f2b] text-xl md:text-2xl">Sistem Simpan Pinjam Anggota</div>
                    <div class="text-sm text-gray-400 hidden sm:block">Koperasi Konsumen Karyawan Balitbang Kemhan</div>
                </div>
            </div>
            <img src="{{ asset('images/Kemhan logo.png') }}" alt="Logo Kemhan" class="h-20 w-20 object-contain">
        </div>

        {{-- BARIS 2: satu dropdown gabungan (ikon ☰), di kanan --}}
        @auth
            <div class="border-t border-gray-100">
                <div class="w-full px-8 flex justify-end items-center">
                    <details class="relative">
                        <summary class="list-none flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 cursor-pointer select-none my-1">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </summary>
                        <div class="absolute right-0 mt-1 w-56 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden z-10 text-sm">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->user()->isAdmin() ? 'Admin' : 'Anggota' }}</div>
                            </div>

                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Dashboard</a>
                                <a href="{{ route('admin.pinjaman.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Kelola Pinjaman</a>
                                <a href="{{ route('admin.pegawai.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Kelola Pegawai</a>
                            @else
                                <a href="{{ route('anggota.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Dashboard</a>
                                <a href="{{ route('anggota.pinjaman.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Pinjaman Saya</a>
                                <a href="{{ route('anggota.pinjaman.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Ajukan Pinjaman</a>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Profil</a>
                            <a href="{{ route('password.change.form') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Ganti Password</a>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        @endauth
    </header>

    <main class="w-full px-8 py-8 flex-1">
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-gray-200 py-6">
        <p class="text-center text-xs text-gray-400">
            Hak Cipta &copy; Kementerian Pertahanan Republik Indonesia
        </p>
    </footer>
</body>
</html>