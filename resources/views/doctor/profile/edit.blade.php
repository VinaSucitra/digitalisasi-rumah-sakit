@extends('layouts.doctor')

@section('page_title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-xs text-emerald-700 uppercase tracking-wide mb-1">Akun Dokter</p>
            <h2 class="text-3xl font-bold text-gray-800">Profil Saya</h2>
            <p class="text-sm text-gray-500">
                Perbarui informasi akun dan detail profesional Anda.
            </p>
        </div>

        <a href="{{ route('doctor.dashboard') }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
            Kembali
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white shadow-xl rounded-2xl p-6 lg:p-8 max-w-2xl">
        <form action="{{ route('doctor.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ====== DATA USER ====== --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-3 py-2 border rounded-lg text-sm
                              focus:ring-emerald-500 focus:border-emerald-500
                              @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-3 py-2 border rounded-lg text-sm
                              focus:ring-emerald-500 focus:border-emerald-500
                              @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ====== DETAIL PROFESIONAL DOKTER ====== --}}
            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4">Detail Profesional</h3>

            {{-- Poli --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">Poli / Spesialisasi</label>
                <select name="poli_id"
                        class="w-full px-3 py-2 border rounded-lg text-sm bg-white
                               focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Tidak Diubah --</option>

                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}"
                            {{ old('poli_id', $doctor->poli_id ?? '') == $poli->id ? 'selected' : '' }}>
                            {{ $poli->name }}
                        </option>
                    @endforeach
                </select>
                @error('poli_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SIP --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-1">SIP (Nomor Izin Praktik)</label>
                <input type="text" name="sip"
                       value="{{ old('sip', $doctor->sip ?? '') }}"
                       class="w-full px-3 py-2 border rounded-lg text-sm
                              focus:ring-emerald-500 focus:border-emerald-500
                              @error('sip') border-red-500 @enderror">
                @error('sip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BIO --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-700 mb-1">Bio / Tentang Saya</label>
                <textarea name="bio" rows="4"
                          class="w-full px-3 py-2 border rounded-lg text-sm
                                 focus:ring-emerald-500 focus:border-emerald-500
                                 @error('bio') border-red-500 @enderror"
                          placeholder="Ceritakan pengalaman Anda...">{{ old('bio', $doctor->bio ?? '') }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TOMBOL --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg shadow-lg
                               hover:bg-emerald-700 transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
