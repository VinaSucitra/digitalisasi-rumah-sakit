@extends('layouts.auth')

@section('title', 'Login - RS Digital')

@section('content')
    <h1 class="text-2xl md:text-3xl font-extrabold text-teal-800 text-center mb-2">
        Selamat Datang Kembali
    </h1>
    <p class="text-sm text-gray-500 text-center mb-8">
        Silakan masukkan detail akun Anda untuk melanjutkan.
    </p>

    {{-- Error global (session) --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validasi error --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                Email atau Nomor Telepon
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required autofocus
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                Password
            </label>
            <input id="password" type="password" name="password"
                   required
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
        </div>

        {{-- Remember --}}
        <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember"
                       class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-teal-600 hover:text-teal-800">
                    Forgot your password?
                </a>
            @endif
        </div>

        {{-- Tombol --}}
        <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm py-2.5 shadow">
                <i class="fas fa-sign-in-alt"></i>
                <span>LOG IN</span>
            </button>
        </div>
    </form>

    {{-- Link ke register --}}
    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-teal-600 font-semibold hover:text-teal-800">
                Daftar Sekarang
            </a>
        </p>
    @endif
@endsection
