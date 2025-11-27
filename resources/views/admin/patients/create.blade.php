@extends('admin.layouts.app')

@section('page_title', 'Tambah Pasien Baru')

@section('content')
<div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-teal-900">Tambah Pasien Baru</h1>
            <p class="text-sm text-gray-500">Admin dapat membuat akun pasien beserta data profilnya.</p>
        </div>
        <a href="{{ route('admin.patients.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl shadow hover:bg-gray-300 transition">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 lg:p-8">

        <form action="{{ route('admin.patients.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Informasi Akun --}}
            <div class="border-b border-gray-100 pb-5">
                <h2 class="text-lg font-semibold text-teal-800 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                    Informasi Akun (User)
                </h2>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('name') border-rose-500 @enderror">
                    @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('email') border-rose-500 @enderror">
                    @error('email')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('password') border-rose-500 @enderror">
                        @error('password')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>
            </div>

            {{-- Detail Pasien --}}
            <div>
                <h2 class="text-lg font-semibold text-teal-800 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                    Detail Pasien
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('birth_date') border-rose-500 @enderror">
                        @error('birth_date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="gender"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('gender') border-rose-500 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('phone') border-rose-500 @enderror">
                    @error('phone')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('address') border-rose-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold shadow hover:bg-teal-700 transition">
                    <i class="fas fa-save mr-2 text-xs"></i>
                    Simpan Pasien
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
