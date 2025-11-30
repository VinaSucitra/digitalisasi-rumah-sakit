<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'RS Digital - Dashboard Tamu')</title>

    {{-- FONT POPPINS --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- TAILWINDCSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FONT AWESOME (ICONS) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Menggunakan scroll-behavior smooth untuk tautan anchor */
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0; 
            /* ❌ PERBAIKAN: Hapus padding-top agar konten menempel ke Navbar */
            padding-top: 0; 
        }

        /* Styling Sidebar & Overlay */
        .sidebar {
            transition: transform 0.3s ease-out;
            transform: translateX(-100%);
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .overlay {
            transition: opacity 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    {{-- ====================================================================== --}}
    {{-- NAVIGATION BAR (FIXED) --}}
    {{-- ====================================================================== --}}
    <nav class="bg-teal-900 shadow-lg fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <div class="flex items-center space-x-4">
                
                {{-- Tombol Menu Mobile --}}
                <button id="menu-button" class="text-white md:hidden hover:text-teal-200 transition">
                    <i class="fa fa-bars text-xl"></i>
                </button>

                {{-- Logo/Nama RS --}}
                <a href="{{ route('guest.home') }}" class="text-2xl font-extrabold text-white uppercase tracking-widest">
                    RS DIGITAL
                </a>
            </div>


            {{-- NAVIGATION DESKTOP --}}
            <div class="hidden md:flex space-x-7 text-sm font-medium text-white items-center">
                <a href="{{ route('guest.home') }}" class="hover:text-teal-200 transition">BERANDA</a>
                <a href="{{ route('guest.home') }}#about-section" class="hover:text-teal-200 transition">TENTANG</a>
                
                {{-- Tautan Dokter & Jadwal (Dibuat Bold untuk Fokus) --}}
                <a href="{{ route('public.doctors') }}" class="hover:text-teal-200 transition font-bold text-teal-300">DOKTER & JADWAL</a>

                <a href="{{ route('guest.home') }}#services-section" class="hover:text-teal-200 transition">LAYANAN</a>
                <a href="{{ route('guest.home') }}#contact-section" class="hover:text-teal-200 transition">KONTAK KAMI</a>
            </div>

            {{-- Auth Buttons (Login/Signup) --}}
            <div class="flex items-center space-x-4">
                
                <a href="{{ route('login') }}" class="text-white text-sm px-4 py-2 font-semibold hover:text-teal-200 transition">
                    LOGIN
                </a>

                <a href="{{ route('register') }}" class="bg-teal-500 text-white text-sm px-4 py-2 rounded-md hover:bg-teal-600 transition font-semibold">
                    SIGN UP
                </a>
            </div>
        </div>
    </nav>

    {{-- ====================================================================== --}}
    {{-- SIDEBAR MOBILE MENU --}}
    {{-- ====================================================================== --}}
    {{-- Sidebar sudah OK karena menggunakan pt-[80px] untuk mengatasi fixed nav --}}
    <div id="sidebar" class="sidebar fixed top-0 left-0 h-full w-64 bg-white shadow-2xl z-50 pt-[80px] md:hidden">
        
        <div class="p-4 space-y-4 flex flex-col">
            <a href="{{ route('guest.home') }}" onclick="closeMenu()" class="text-gray-700 hover:text-teal-600 transition font-medium border-b pb-2">BERANDA</a>
            <a href="{{ route('guest.home') }}#about-section" onclick="closeMenu()" class="text-gray-700 hover:text-teal-600 transition font-medium border-b pb-2">TENTANG</a>

            {{-- Tautan Dokter di Mobile --}}
            <a href="{{ route('public.doctors') }}" onclick="closeMenu()" class="text-teal-700 hover:text-teal-600 transition font-bold border-b pb-2">DOKTER & JADWAL</a>

            <a href="{{ route('guest.home') }}#services-section" onclick="closeMenu()" class="text-gray-700 hover:text-teal-600 transition font-medium border-b pb-2">LAYANAN</a>
            <a href="{{ route('guest.home') }}#contact-section" onclick="closeMenu()" class="text-gray-700 hover:text-teal-600 transition font-medium border-b pb-2">KONTAK KAMI</a>
            
            
        </div>
    </div>

    {{-- Overlay untuk Mobile Menu --}}
    <div id="overlay" class="fixed inset-0 bg-black opacity-0 pointer-events-none z-40 md:hidden overlay" onclick="closeMenu()"></div>


    {{-- ====================================================================== --}}
    {{-- KONTEN UTAMA (DIISI OLEH @yield('hero') dan @yield('content')) --}}
    {{-- Sekarang Hero Section Anda (index.blade.php) harus memiliki pt-[80px] agar konten tidak tertutup. --}}
    {{-- ====================================================================== --}}
    <main class="min-h-screen"> 
        @yield('hero')
        @yield('content')
    </main>


    {{-- ====================================================================== --}}
    {{-- FOOTER SECTION --}}
    {{-- ====================================================================== --}}
    <footer class="bg-teal-900 text-white mt-16 py-10">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-8">


            {{-- Links --}}
            <div>
                <h4 class="font-semibold mb-4 text-teal-300">Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('guest.home') }}#hero-section" class="hover:text-teal-200">Beranda</a></li>
                    
                    {{-- Tautan Dokter & Jadwal di Footer --}}
                    <li><a href="{{ route('public.doctors') }}" class="hover:text-teal-200 font-medium">Dokter & Jadwal</a></li>
                    
                    <li><a href="{{ route('guest.home') }}#services-section" class="hover:text-teal-200">Layanan</a></li>
                    <li><a href="{{ route('guest.home') }}#contact-section" class="hover:text-teal-200">Kontak Kami</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-teal-200">Daftar</a></li>
                </ul>
            </div>
            
            {{-- Contact Info --}}
            <div>
                <h4 class="font-semibold mb-4 text-teal-300">Contact Info</h4>
                <p class="text-sm mb-2"><i class="fa fa-map-marker-alt mr-3"></i> Alamat Rumah Sakit Anda</p>
                <p class="text-sm mb-2"><i class="fa fa-phone mr-3"></i> Telepon Kontak</p>
                <p class="text-sm mb-2"><i class="fa fa-envelope mr-3"></i> Email Anda</p>
            </div>

            {{-- About RS Digital --}}
            <div>
                <h4 class="font-semibold mb-4 text-teal-300">About RS Digital</h4>
                <p class="text-sm">
                    Kami berkomitmen menyediakan pelayanan kesehatan terbaik dengan sentuhan digital untuk kemudahan Anda.
                </p>
            </div>

        </div>

        <div class="text-center text-gray-400 text-xs mt-10 border-t border-teal-800 pt-5">
            © {{ date('Y') }} RS Digital. All rights reserved.
        </div>
    </footer>

    {{-- ====================================================================== --}}
    {{-- JAVASCRIPT UNTUK SIDEBAR MOBILE --}}
    {{-- ====================================================================== --}}
    <script>
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            sidebar.classList.toggle('open');
            // Menghilangkan pointer-events-none saat menu terbuka
            if (sidebar.classList.contains('open')) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-50');
            } else {
                closeMenu();
            }
        }

        function closeMenu() {
            sidebar.classList.remove('open');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.classList.remove('opacity-50');
        }

        menuButton.addEventListener('click', toggleMenu);
    </script>

</body>

</html>