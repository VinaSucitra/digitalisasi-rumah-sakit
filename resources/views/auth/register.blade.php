@extends('layouts.guest')

@section('title', 'Registrasi Akun')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    
    {{-- Card Registrasi: Fokus di tengah, border atas Teal, Shadow --}}
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-lg border-t-4 border-teal-500">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-teal-700">Daftar Akun Baru</h2>
            <p class="text-gray-500 text-sm">Isi data diri Anda di bawah ini</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="name" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" type="password" name="password" required autocomplete="new-password" />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input id="password_confirmation" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-teal-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition" href="{{ route('login') }}">
                    Sudah Punya Akun?
                </a>

                <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection