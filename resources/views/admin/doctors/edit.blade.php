@extends('admin.layouts.app')

@section('page_title', 'Edit Dokter')

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
                <i class="fas fa-user-doctor text-white/90"></i>
                Edit Dokter: {{ $doctor->name }}
            </h1>
            <p class="text-sm text-teal-100 mt-1 max-w-xl">
                Perbarui informasi akun dan detail dokter untuk memastikan data tetap akurat.
            </p>
        </div>

        <a href="{{ route('admin.doctors.index') }}"
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

            <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" class="space-y-8">
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
                                Data login dan identitas dasar dokter.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $doctor->name) }}" required
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('name') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('name')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $doctor->email) }}" required
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('email') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('email')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru (opsional)
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('password') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('password')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-gray-400 mt-1">
                                Kosongkan jika tidak ingin mengubah password.
                            </p>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                {{-- DETAIL DOKTER --}}
                @php
                    $detail = $doctor->doctorDetail;
                @endphp

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-teal-800 uppercase tracking-wide">
                                Detail Dokter
                            </h2>
                            <p class="text-[11px] text-gray-500">
                                Informasi layanan dan profil dokter di rumah sakit.
                            </p>
                        </div>
                    </div>

                    {{-- Poli --}}
                    <div>
                        <label for="poli_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Poli / Spesialisasi
                        </label>
                        <div class="relative">
                            <select name="poli_id" id="poli_id" required
                                    class="w-full px-3 py-2.5 pr-9 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                           focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                           @error('poli_id') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($polis as $poli)
                                    <option value="{{ $poli->id }}"
                                        {{ (string) old('poli_id', $detail->poli_id ?? '') === (string) $poli->id ? 'selected' : '' }}>
                                        {{ $poli->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </span>
                        </div>
                        @error('poli_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SIP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label for="sip" class="block text-sm font-medium text-gray-700 mb-1">
                                No. SIP (opsional)
                            </label>
                            <input type="text" name="sip" id="sip"
                                   value="{{ old('sip', $detail->sip ?? '') }}"
                                   class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                          @error('sip') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @error('sip')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bio --}}
                        <div class="lg:col-span-1">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                                Bio Singkat (opsional)
                            </label>
                            <textarea name="bio" id="bio" rows="3"
                                      class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                             focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                             @error('bio') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">{{ old('bio', $detail->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a href="{{ route('admin.doctors.index') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white
                              text-xs sm:text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2 text-[10px]"></i>
                        Batal
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-teal-600 text-white
                                   text-xs sm:text-sm font-semibold shadow-md hover:bg-teal-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-teal-500">
                        <i class="fas fa-save mr-2 text-[11px]"></i>
                        Perbarui Data Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
