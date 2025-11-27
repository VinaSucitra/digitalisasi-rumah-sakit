@extends('admin.layouts.app')

@section('page_title', 'Tambah Dokter Baru')

@section('content')
<div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-teal-900">Tambah Dokter Baru</h1>
            <p class="text-sm text-gray-500">Buat akun dan profil dokter baru untuk sistem rumah sakit.</p>
        </div>
        <a href="{{ route('admin.doctors.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl shadow hover:bg-gray-300 transition">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 lg:p-8">

        <form action="{{ route('admin.doctors.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Informasi Akun --}}
            <div class="border-b border-gray-100 pb-5">
                <h2 class="text-lg font-semibold text-teal-800 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                    Informasi Akun (User)
                </h2>

                {{-- Nama --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('email') border-rose-500 @enderror">
                    @error('email')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('password') border-rose-500 @enderror">
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>
            </div>

            {{-- Detail Dokter --}}
            <div>
                <h2 class="text-lg font-semibold text-teal-800 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                    Detail Dokter
                </h2>

                {{-- Poli --}}
                <div class="mb-4">
                    <label for="poli_id" class="block text-sm font-medium text-gray-700 mb-1">Poli / Spesialisasi</label>
                    <select name="poli_id" id="poli_id" required
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('poli_id') border-rose-500 @enderror">
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('poli_id')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SIP --}}
                <div class="mb-4">
                    <label for="sip" class="block text-sm font-medium text-gray-700 mb-1">No. SIP (opsional)</label>
                    <input type="text" name="sip" id="sip" value="{{ old('sip') }}"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('sip') border-rose-500 @enderror">
                    @error('sip')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bio --}}
                <div class="mb-2">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio / Catatan (opsional)</label>
                    <textarea name="bio" id="bio" rows="3"
                              class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('bio') border-rose-500 @enderror">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400 mt-1">
                        Contoh: pengalaman, jadwal umum, atau catatan singkat terkait dokter.
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold shadow hover:bg-teal-700 transition">
                    <i class="fas fa-save mr-2 text-xs"></i>
                    Simpan Dokter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
