<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Doctor Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Icon (Font Awesome 6.5.1) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="min-h-screen bg-teal-50 flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-teal-900 text-teal-50 flex flex-col shadow-xl">
        <div class="px-5 py-4 border-b border-teal-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center">
                    <i class="fas fa-user-md text-xl"></i> {{-- Mengganti ikon menjadi dokter --}}
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-teal-200">Doctor Panel</p>
                    <h1 class="font-bold text-lg leading-tight">DR. DASHBOARD</h1>
                </div>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            <p class="px-3 text-[11px] font-semibold tracking-wide text-teal-400 uppercase mb-1">
                Dashboard
            </p>
            <a href="{{ route('doctor.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg
                      {{ request()->routeIs('doctor.dashboard') ? 'bg-teal-700 text-white' : 'hover:bg-teal-800/70 text-teal-100' }}">
                <i class="fas fa-home w-5"></i>
                <span>Dashboard</span>
            </a>

            <p class="px-3 text-[11px] font-semibold tracking-wide text-teal-400 uppercase mt-4 mb-1">
                Aktivitas
            </p>
            <a href="{{ route('doctor.appointments.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg
                      {{ request()->is('doctor/appointments*') ? 'bg-teal-700' : 'hover:bg-teal-800/70' }}">
                <i class="fas fa-calendar-check w-5"></i>
                <span>Janji Temu Saya</span>
            </a>
            <a href="{{ route('doctor.medical_records.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg
                      {{ request()->is('doctor/medical_records*') ? 'bg-teal-700' : 'hover:bg-teal-800/70' }}">
                <i class="fas fa-notes-medical w-5"></i>
                <span>Rekam Medis</span>
            </a>
        </nav>

        {{-- FOOTER USER & LOGOUT --}}
        <div class="border-t border-teal-800 px-4 py-3 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-teal-700 flex items-center justify-center">
                    <i class="fas fa-user-shield text-teal-50 text-sm"></i>
                </div>
                <div>
                    <p class="font-semibold">
                        {{ auth()->user()->name ?? 'Dokter' }}
                    </p>
                    <p class="text-teal-300 text-[11px]">
                        {{ auth()->user()->email ?? 'dokter@hospital.com' }}
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-teal-200 hover:text-white hover:bg-teal-700 rounded-full p-2 transition">
                    <i class="fas fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col">
        {{-- Top bar --}}
        <header class="h-14 flex items-center justify-between px-6 border-b bg-white/70 backdrop-blur">
            <div class="flex items-center gap-2 text-sm text-teal-900">
                <i class="fas fa-tachometer-alt"></i>
                <span>@yield('page_title', 'Dashboard Dokter')</span>
            </div>
            {{-- Tambahkan user profile/notifikasi di sini jika perlu --}}
        </header>

        <section class="flex-1 p-4 md:p-6 lg:p-8">
            @yield('content')
        </section>
    </main>

</body>
</html>