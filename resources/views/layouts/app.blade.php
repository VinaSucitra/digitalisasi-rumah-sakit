<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts (Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        {{-- ========================================================================= --}}
        {{-- SIDEBAR KIRI (Navigasi Samping) - LOKASI YANG ANDA TANYAKAN --}}
        {{-- Menggunakan warna Deep Gray (stone-800) --}}
        {{-- ========================================================================= --}}
        <div id="sidebar" class="flex-shrink-0 w-64 bg-stone-800 text-white shadow-xl overflow-y-auto transition-all duration-300 ease-in-out z-30">
            <div class="p-6">
                <h1 class="text-2xl font-extrabold text-rose-400 uppercase tracking-widest">
                    RS ADMIN
                </h1>
                <p class="text-xs text-stone-400 mt-1">Panel Kontrol</p>
            </div>

            <nav class="mt-2 space-y-1 px-4">
                {{-- Kategori: Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('admin.dashboard') ? 'bg-rose-700 font-semibold shadow-md' : 'text-stone-300 hover:bg-stone-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <div class="mt-4 pt-4 border-t border-stone-700">
                    <p class="text-xs uppercase text-stone-500 font-bold mb-2 tracking-wider">Manajemen Data</p>
                </div>

                {{-- Kategori: Data Master --}}
                @php
                    $masterDataRoutes = ['admin.doctors.index', 'admin.patients.index', 'admin.polis.index'];
                    $isActiveMaster = in_array(request()->route()->getName(), $masterDataRoutes);
                @endphp

                <a href="{{ route('admin.doctors.index') }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('admin.doctors.index') ? 'bg-rose-700 font-semibold shadow-md' : 'text-stone-300 hover:bg-stone-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 100 5.292m-4.5 5.836a8 8 0 0011.5 0M8 21v-1a4 4 0 014-4h.5M19 16l1.25-1.25"></path></svg>
                    Dokter
                </a>

                <a href="{{ route('admin.patients.index') }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('admin.patients.index') ? 'bg-rose-700 font-semibold shadow-md' : 'text-stone-300 hover:bg-stone-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a1 1 0 01-1 1h-1a7 7 0 00-14 0H4a1 1 0 01-1-1v10a1 1 0 001 1h16a1 1 0 001-1V10z"></path></svg>
                    Pasien
                </a>

                <a href="{{ route('admin.polis.index') }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('admin.polis.index') ? 'bg-rose-700 font-semibold shadow-md' : 'text-stone-300 hover:bg-stone-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1h2a1 1 0 001-1v-1zM7 20a1 1 0 00-1-1H4a1 1 0 00-1 1v1a1 1 0 001 1h2a1 1 0 001-1v-1zM13 20a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1h2a1 1 0 001-1v-1zM19 9a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V9zM7 9a1 1 0 00-1-1H4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V9zM13 9a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V9zM19 3a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V3zM7 3a1 1 0 00-1-1H4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V3zM13 3a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V3z"></path></svg>
                    Poli/Klinik
                </a>

                <div class="mt-4 pt-4 border-t border-stone-700">
                    <p class="text-xs uppercase text-stone-500 font-bold mb-2 tracking-wider">Transaksi & Layanan</p>
                </div>

                {{-- Kategori: Transaksi --}}
                <a href="{{ route('admin.appointments.index') }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('admin.appointments.index') ? 'bg-rose-700 font-semibold shadow-md' : 'text-stone-300 hover:bg-stone-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Janji Temu
                </a>

                {{-- Tambahkan link untuk Obat/Medical Records/Prescriptions di sini jika sudah dibuat --}}

            </nav>
        </div>

        {{-- Konten Utama (Header + Main) --}}
        <div class="flex flex-col flex-1 overflow-x-hidden overflow-y-auto">

            {{-- ========================================================================= --}}
            {{-- TOPBAR ATAS (Samping Navbar) - LOKASI YANG ANDA TANYAKAN --}}
            {{-- Menggunakan warna Deep Gray (stone-700) untuk kontras --}}
            {{-- ========================================================================= --}}
            <header class="flex-shrink-0 bg-white border-b border-gray-200 shadow-md">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-end h-16">
                        <div class="flex items-center">
                            {{-- Dropdown Profile/Logout --}}
                            <div class="ml-4 flex items-center md:ml-6 relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <span class="font-semibold text-gray-700 mr-2">Admin</span>
                                    <img class="h-8 w-8 rounded-full bg-gray-200 p-1" src="https://placehold.co/32x32/rose500/white?text=A" alt="Admin Profile">
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-40" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                        Profil
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-rose-100 hover:text-rose-700" role="menuitem" tabindex="-1">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content Slot --}}
            <main class="flex-1">
                @yield('content')
            </main>

        </div>
    </div>
    
    {{-- Memastikan AlpineJS tersedia untuk dropdown --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>