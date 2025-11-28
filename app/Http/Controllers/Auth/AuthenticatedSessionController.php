<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');  // Menampilkan halaman login
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi pengguna
        $request->authenticate();

        // Regenerasi sesi untuk meningkatkan keamanan
        $request->session()->regenerate();

        // Periksa role pengguna dan arahkan sesuai role mereka
        if (auth()->user()->role == 'doctor') {
            // Jika role-nya 'dokter', arahkan ke dashboard dokter
            return redirect()->route('doctor.dashboard');
        } elseif (auth()->user()->role == 'admin') {
            // Jika role-nya 'admin', arahkan ke dashboard admin
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role == 'patient') {
            // Jika role-nya 'pasien', arahkan ke dashboard pasien
            return redirect()->route('patient.dashboard');
        }

        // Jika role tidak dikenali, arahkan ke halaman utama
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout pengguna dari sesi
        Auth::guard('web')->logout();

        // Hapus data sesi
        $request->session()->invalidate();

        // Regenerasi token untuk menghindari serangan CSRF
        $request->session()->regenerateToken();

        // Redirect ke halaman utama setelah logout
        return redirect('/');
    }
}
