<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'RS Digital - Autentikasi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FontAwesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- ================= HEADER KECIL ================= --}}
    <header class="w-full bg-teal-800 text-white shadow">
        <div class="max-w-5xl mx-auto flex items-center justify-between px-6 py-3">

            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center shadow">
                    <i class="fas fa-hospital text-lg"></i>
                </div>
                <span class="font-bold tracking-wide text-lg">RS DIGITAL</span>
            </div>

            <a href="{{ url('/') }}"
               class="text-sm hover:text-gray-200 transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </header>

    {{-- ================= MAIN (CARD TENGAH) ================= --}}
    <main class="flex-1 flex items-center justify-center px-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-200 p-10 my-10">
            @yield('content')
        </div>
    </main>

    <footer class="py-4 text-center text-gray-500 text-xs">
        © {{ date('Y') }} RS Digital — All Rights Reserved
    </footer>

</body>
</html>
