<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title', 'Patient Portal')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR KIRI: Teal-800 / Emerald-400 (Panel Pasien - Disesuaikan dengan tema Dokter) --}}
        <div id="sidebar" class="flex-shrink-0 w-64 bg-teal-800 text-white shadow-xl overflow-y-auto transition-all duration-300 ease-in-out z-30">
            <div class="p-6">
                <h1 class="text-2xl font-extrabold text-emerald-400 uppercase tracking-widest">
                    RS PORTAL
                </h1>
                <p class="text-xs text-teal-400 mt-1">Halaman Pasien</p>
            </div>

            <nav class="mt-2 space-y-1 px-4">
                {{-- Kategori: Dashboard --}}
                <a href="{{ route('patient.dashboard') }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('patient.dashboard') ? 'bg-teal-700 font-semibold shadow-md' : 'text-teal-300 hover:bg-teal-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    Dashboard
                </a>

                <div class="mt-4 pt-4 border-t border-teal-700">
                    <p class="text-xs uppercase text-teal-500 font-bold mb-2 tracking-wider">Layanan</p>
                </div>

                {{-- Kategori: Layanan --}}
                <a href="{{ route('patient.appointments.index') ?? '#' }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('patient.appointments.*') ? 'bg-teal-700 font-semibold shadow-md' : 'text-teal-300 hover:bg-teal-700 hover:text-white' }}">
                    <i class="fas fa-calendar-plus w-5 h-5 mr-3"></i>
                    Buat Janji Temu
                </a>

                <a href="{{ route('patient.medical_records.index') ?? '#' }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('patient.medical_records.*') ? 'bg-teal-700 font-semibold shadow-md' : 'text-teal-300 hover:bg-teal-700 hover:text-white' }}">
                    <i class="fas fa-history w-5 h-5 mr-3"></i>
                    Riwayat Pemeriksaan
                </a>
                
                {{-- Tambahan --}}
                <div class="mt-4 pt-4 border-t border-teal-700">
                    <p class="text-xs uppercase text-teal-500 font-bold mb-2 tracking-wider">Informasi</p>
                </div>

                <a href="{{ route('patient.doctor_schedules.index') ?? '#' }}"
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out
                   {{ request()->routeIs('patient.doctor_schedules.index') ? 'bg-teal-700 font-semibold shadow-md' : 'text-teal-300 hover:bg-teal-700 hover:text-white' }}">
                    <i class="fas fa-user-md w-5 h-5 mr-3"></i>
                    Jadwal Dokter
                </a>
            </nav>
        </div>

        {{-- Konten Utama (Header + Main) --}}
        <div class="flex flex-col flex-1 overflow-x-hidden overflow-y-auto">

            {{-- TOPBAR ATAS --}}
            <header class="flex-shrink-0 bg-white border-b border-teal-200 shadow-sm">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-end h-16">
                        <div class="flex items-center">
                            {{-- Dropdown Profile/Logout --}}
                            <div class="ml-4 flex items-center md:ml-6 relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <span class="font-semibold text-gray-700 mr-2">{{ Auth::user()->name ?? 'Pasien' }}</span>
                                    {{-- Menggunakan warna teal pada placeholder --}}
                                    <img class="h-8 w-8 rounded-full bg-gray-200 p-1" src="https://placehold.co/32x32/teal500/white?text=P" alt="Patient Profile">
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
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-teal-100 hover:text-teal-700" role="menuitem" tabindex="-1">
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
            <main class="flex-1 p-6">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>