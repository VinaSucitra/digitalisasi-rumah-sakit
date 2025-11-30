@extends('layouts.doctor')

@section('page_title', 'Tambah Jadwal')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-500 to-emerald-500 rounded-2xl px-6 py-5 shadow-md text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">Jadwal Praktik</p>
            <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-2">
                <i class="fas fa-clock text-white/90"></i>
                Tambah Jadwal Praktik
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Pilih hari dan jam mulai. Sistem akan otomatis membuat slot dengan durasi
                <span class="font-semibold">30 menit</span>.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2 text-xs">
            <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2">
                <p class="text-[10px] uppercase tracking-widest text-teal-100">Langkah</p>
                <p class="font-semibold text-sm">1. Pilih hari &nbsp; • &nbsp; 2. Pilih jam mulai</p>
            </div>

            <a href="{{ route('doctor.schedules.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 text-white text-sm font-semibold border border-white/40 hover:bg-white hover:text-teal-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Jadwal Saya
            </a>
        </div>
    </div>

    {{-- FORM CARD (CENTERED) --}}
    <div class="w-full flex justify-center">
        <div class="w-full max-w-xl">
            <div class="bg-white shadow-sm border border-teal-50 rounded-2xl p-6 lg:p-7 space-y-6">

                {{-- Header kecil --}}
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-teal-600 uppercase">Form Jadwal</p>
                        <p class="text-sm text-gray-500">Pastikan tidak ada jadwal yang bertabrakan dengan jadwal lain.</p>
                    </div>
                </div>

                <form action="{{ route('doctor.schedules.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Hari --}}
                    <div class="space-y-1">
                        <label for="day_of_week" class="block text-sm font-semibold text-gray-800">
                            Hari Praktik <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="day_of_week" id="day_of_week" required
                                    class="w-full px-3 py-2.5 pr-9 border rounded-lg text-sm
                                           border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                           @error('day_of_week') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                                <option value="" class="text-gray-400">-- Pilih Hari --</option>
                                @foreach ($days as $key => $label)
                                    <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Contoh: Senin untuk jadwal praktik setiap hari Senin.
                        </p>
                        @error('day_of_week')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jam mulai --}}
                    <div class="space-y-1">
                        <label for="start_time" class="block text-sm font-semibold text-gray-800">
                            Jam Mulai Praktik <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_auto] gap-3 items-center">
                            <div class="relative">
                                <input type="time" name="start_time" id="start_time"
                                       value="{{ old('start_time') }}"
                                       required
                                       class="w-full px-3 py-2.5 border rounded-lg text-sm
                                              border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                              @error('start_time') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                    <i class="fas fa-clock text-[11px]"></i>
                                </span>
                            </div>

                            <div class="text-xs text-gray-500 bg-teal-50 text-teal-700 px-3 py-2 rounded-lg border border-teal-100">
                                Durasi otomatis: <span class="font-semibold">30 menit</span>
                            </div>
                        </div>

                        <p class="text-[11px] text-gray-400 mt-1">
                            Misal Anda pilih <span class="font-semibold">09:00</span>, maka jadwal akan tercatat
                            sebagai <span class="font-semibold">09:00 - 09:30</span>.
                        </p>

                        @error('start_time')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Validasi --}}
                    <div class="flex items-start gap-2 text-[11px] text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                        <i class="fas fa-triangle-exclamation mt-0.5"></i>
                        <p>
                            Sistem akan memeriksa agar jadwal baru <span class="font-semibold">tidak tumpang tindih</span>
                            dengan jadwal Anda yang sudah ada pada hari yang sama.
                        </p>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3">
                        <button type="button"
                                onclick="window.location='{{ route('doctor.schedules.index') }}'"
                                class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-medium rounded-lg border border-gray-300 text-gray-600 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </button>

                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 text-xs sm:text-sm font-semibold rounded-lg shadow-md
                                       bg-teal-600 text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-teal-500">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Jadwal
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
