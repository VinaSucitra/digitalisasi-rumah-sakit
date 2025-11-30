@extends('admin.layouts.app')

@section('page_title', 'Edit Pasien')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Manajemen Data
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-user-edit text-white/90"></i>
                Edit Pasien: {{ $patient->name }}
            </h1>
            <p class="text-sm text-teal-100 mt-1 max-w-xl">
                Perbarui data akun dan profil pasien.
            </p>
        </div>

        <a href="{{ route('admin.patients.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 border border-white/40
                  text-xs sm:text-sm font-semibold text-white hover:bg-white hover:text-teal-700
                  transition shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-[10px]"></i>
            Kembali ke Daftar
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 lg:p-8">
            @php
                $detail = $patient->patient;
            @endphp

            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- INFORMASI AKUN --}}
                <div class="border-b border-gray-100 pb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-teal-800 uppercase tracking-wide">
                                Informasi Akun (User)
                            </h2>
                            <p class="text-[11px] text-gray-500">
                                Data login yang digunakan pasien pada sistem.
                            </p>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}" required
                               class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                      @error('name') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}" required
                               class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                      @error('email') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password baru --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru (opsional)
                            </label>
                            <input type="password" name="password"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('password') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('password')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                {{-- DETAIL PASIEN --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-teal-800 uppercase tracking-wide">
                                Detail Pasien
                            </h2>
                            <p class="text-[11px] text-gray-500">
                                Informasi rekam medis dasar pasien.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- No RM --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. Rekam Medis
                            </label>
                            <input type="text" value="{{ $detail->no_rm ?? '-' }}" disabled
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm shadow-sm">
                        </div>

                        {{-- Tanggal lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Lahir
                            </label>
                            <input type="date" name="birth_date"
                                   value="{{ old('birth_date', optional($detail)->birth_date) }}"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('birth_date') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('birth_date')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- Gender --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kelamin
                            </label>
                            <div class="relative">
                                <select name="gender"
                                        class="w-full px-3 py-2.5 pr-9 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                               focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                               @error('gender') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender', optional($detail)->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', optional($detail)->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </span>
                            </div>
                            @error('gender')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. HP
                            </label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', optional($detail)->phone) }}"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('phone') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('phone')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea name="address" rows="3"
                                  class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                         focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                         @error('address') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">{{ old('address', optional($detail)->address) }}</textarea>
                        @error('address')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a href="{{ route('admin.patients.index') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white
                              text-xs sm:text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2 text-[10px]"></i>
                        Batal
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-teal-600
                                   text-white text-xs sm:text-sm font-semibold shadow-md hover:bg-teal-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-teal-500">
                        <i class="fas fa-save mr-2 text-[11px]"></i>
                        Perbarui Data Pasien
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
