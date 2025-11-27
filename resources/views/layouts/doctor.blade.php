<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar {
            /* Menyesuaikan warna dengan gambar tangkapan layar */
            background-color: #1e3a8a; 
        }
        .logout-btn {
            /* Gaya tombol logout */
            color: #fca5a5; /* Red-300 */
            transition: all 0.2s;
        }
        .logout-btn:hover {
            color: white;
            background-color: #dc2626; /* Red-600 */
        }
    </style>
</head>
<body class="flex">

    <!-- Sidebar / Panel Samping -->
    <aside class="w-64 sidebar text-white min-h-screen p-4 flex flex-col justify-between">
        <div>
            <h1 class="text-2xl font-extrabold mb-8 text-center">DOCTOR PANEL</h1>

            <!-- Navigasi Menu Utama -->
            <nav class="space-y-2">
                <a href="{{ route('doctor.dashboard') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition duration-150">
                    <i class="fas fa-home w-5 mr-3"></i> Dashboard
                </a>

                <a href="{{ route('doctor.appointments.index') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition duration-150">
                    <i class="fas fa-calendar-check w-5 mr-3"></i> Janji Temu Saya
                </a>

                <a href="{{ route('doctor.medical_records.index') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition duration-150">
                    <i class="fas fa-notes-medical w-5 mr-3"></i> Rekam Medis
                </a>
            </nav>
        </div>

        <!-- Profil dan Logout -->
        <div class="space-y-2 border-t border-blue-700 pt-4">
            
            <!-- Tombol Profil -->
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition duration-150">
                <i class="fas fa-user-circle w-5 mr-3"></i> Profil Saya
            </a>

            <!-- Form Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" 
                        class="w-full text-left flex items-center p-3 rounded-lg logout-btn font-semibold">
                    <i class="fas fa-sign-out-alt w-5 mr-3"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">@yield('header')</h1>
        @yield('content')
    </main>

</body>
</html>