@extends('layouts.patient')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Pasien')

@section('content')
    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 max-w-4xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-100 pb-4">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-full bg-teal-100 flex items-center justify-center">
                    <span class="text-xl font-bold text-teal-800">
                        {{ strtoupper(substr($user->name ?? auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Profil Pasien</h2>
                    <p class="text-sm text-gray-500">
                        Kelola informasi akun dan data pribadi Anda.
                    </p>
                </div>
            </div>

            <div class="text-right text-xs md:text-sm text-gray-500 space-y-1">
                @if(!empty($detail?->no_rm))
                    <p>
                        <span class="font-semibold text-gray-700">No. Rekam Medis:</span>
                        <span class="font-mono text-teal-700">{{ $detail->no_rm }}</span>
                    </p>
                @endif
                <p>
                    <span class="font-semibold text-gray-700">Email Login:</span>
                    <span>{{ $user->email }}</span>
                </p>
            </div>
        </div>

        {{-- FLASH SUKSES --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ERROR GLOBAL --}}
        @if($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold mb-1">Terjadi kesalahan pada pengisian data:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('patient.profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- INFORMASI AKUN --}}
            <section class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="h-5 w-1.5 rounded-full bg-teal-500"></span>
                    <h3 class="text-lg font-semibold text-teal-800">Informasi Akun</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('name') border-rose-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('email') border-rose-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Baru <span class="text-xs text-gray-400">(opsional)</span>
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('password') border-rose-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-[11px] text-gray-400">
                            Kosongkan jika tidak ingin mengubah password.
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Password Baru
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>
            </section>

            {{-- INFORMASI PRIBADI --}}
            <section class="space-y-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="h-5 w-1.5 rounded-full bg-teal-500"></span>
                    <h3 class="text-lg font-semibold text-teal-800">Informasi Pribadi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Lahir
                        </label>
                        <input
                            id="birth_date"
                            name="birth_date"
                            type="date"
                            value="{{ old('birth_date', optional($detail)->birth_date) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('birth_date') border-rose-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kelamin
                        </label>
                        <select
                            id="gender"
                            name="gender"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('gender') border-rose-500 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('gender', optional($detail)->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', optional($detail)->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            No. HP
                        </label>
                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone', optional($detail)->phone) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('phone') border-rose-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(!empty($detail?->no_rm))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. Rekam Medis
                            </label>
                            <input
                                type="text"
                                value="{{ $detail->no_rm }}"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm shadow-sm"
                                disabled>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                               @error('address') border-rose-500 @enderror">{{ old('address', optional($detail)->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-[11px] text-gray-400">
                        Cantumkan alamat lengkap untuk keperluan administrasi rumah sakit.
                    </p>
                </div>
            </section>

            <div class="pt-4 border-t border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                <p class="text-xs text-gray-400">
                    Perubahan data akan digunakan pada proses pendaftaran dan rekam medis berikutnya.
                </p>
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('patient.dashboard') }}"
                       class="px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold shadow hover:bg-teal-700 transition">
                        <i class="fas fa-save text-xs mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
