<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    // app/Http/Middleware/RoleMiddleware.php

// ...

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Ambil role pengguna dan ubah menjadi huruf kecil untuk perbandingan
        $userRole = strtolower($user->role); // <-- PERBAIKAN PENTING DI SINI
        
        // Ubah daftar roles yang diizinkan dari route juga menjadi huruf kecil
        $allowedRoles = array_map('strtolower', $roles); // <-- PERBAIKAN PENTING DI SINI

        // Bandingkan role pengguna dengan daftar role yang diizinkan
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // Jika otorisasi gagal (sebelumnya menyebabkan redirect ke home)
        // Sebaiknya arahkan ke 403 (Akses Ditolak) agar Anda tahu itu masalah role, bukan masalah redirect.
        return abort(403, 'Anda tidak memiliki akses ke halaman ini.'); 
        // Jika Anda TIDAK ingin 403, Anda bisa menggunakan: return redirect()->route('guest.home');
    }
}