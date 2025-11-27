@extends('layouts.guest')

@section('title', 'Login')

{{-- Halaman Login disetel agar terpusat di tengah --}}
@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    
    {{-- Card Login: Maksimal lebar medium (sm:max-w-md), shadow, dan border atas Teal --}}
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-lg border-t-4 border-teal-500">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-teal-700">Selamat Datang Kembali</h2>
            <p class="text-gray-500 text-sm">Silakan masukkan detail akun Anda</p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email atau Nomor Telepon</label>
                <input id="email" 
                       class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" 
                       class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password" />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" 
                           class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" 
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-teal-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button type="submit" 
                        class="ms-4 inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Log in') }}
                </button>
            </div>
            
            <div class="mt-4 text-center text-sm">
                <p class="text-gray-600">Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-800 transition">Daftar Sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection