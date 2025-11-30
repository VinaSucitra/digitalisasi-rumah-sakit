@extends('layouts.auth')

@section('title', 'Daftar Akun - RS Digital')

@section('content')
    <h1 class="text-2xl md:text-3xl font-extrabold text-teal-800 text-center mb-2">
        Daftar Akun Pasien
    </h1>
    <p class="text-sm text-gray-500 text-center mb-8">
        Buat akun baru untuk mengakses layanan janji temu dan rekam medis.
    </p>

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Nama --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                Nama Lengkap
            </label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}"
                   required autofocus
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                Alamat Email
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required
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

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">
                Konfirmasi Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
        </div>

        {{-- Tombol daftar --}}
        <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm py-2.5 shadow">
                <i class="fas fa-user-plus"></i>
                <span>DAFTAR SEKARANG</span>
            </button>
        </div>
    </form>

    {{-- Link ke login --}}
    @if (Route::has('login'))
        <p class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:text-teal-800">
                Login di sini
            </a>
        </p>
    @endif
@endsection
