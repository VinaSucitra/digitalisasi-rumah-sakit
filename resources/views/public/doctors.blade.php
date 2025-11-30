{{-- resources/views/public/doctors.blade.php --}}
@extends('layouts.guest')

@section('title', 'Daftar Dokter & Jadwal')

@php
    use App\Models\Poli;

    // Fallback: kalau controller tidak mengirim $polis, ambil semua poli
    $polis = $polis ?? Poli::all();
@endphp

@section('content')
    <section class="py-16 pt-24 bg-gray-50"> {{-- pt-24 supaya tidak ketutup navbar --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER HALAMAN --}}
            <header class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-extrabold text-teal-800 mb-3">
                    Jadwal Praktik Dokter
                </h1>
                <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto">
                    Lihat daftar dokter dan jadwal praktik mereka. Halaman ini hanya untuk informasi publik.
                    Untuk membuat janji temu, pasien perlu login terlebih dahulu.
                </p>
            </header>

            {{-- FILTER & PENCARIAN --}}
            <div class="mb-10 bg-white rounded-2xl shadow-md border border-gray-100 p-4 md:p-6">
                <form action="{{ url('/doctors') }}" method="GET"
                      class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-end">

                    {{-- Pencarian Nama --}}
                    <div class="space-y-1">
                        <label for="search" class="block text-xs font-semibold text-gray-600 uppercase">
                            Cari Nama Dokter
                        </label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Masukkan nama dokter..."
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        >
                    </div>

                    {{-- Filter Poli --}}
                    <div class="space-y-1">
                        <label for="poli_id" class="block text-xs font-semibold text-gray-600 uppercase">
                            Filter Spesialisasi / Poli
                        </label>
                        <select
                            id="poli_id"
                            name="poli_id"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        >
                            <option value="">-- Semua Spesialisasi --</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}"
                                    {{ (string) request('poli_id') === (string) $poli->id ? 'selected' : '' }}>
                                    {{ $poli->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Cari --}}
                    <div>
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-lg
                                       bg-teal-600 text-white text-sm font-semibold shadow-md
                                       hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- DAFTAR DOKTER (GRID) --}}
            @if($doctors->isEmpty())
                <div class="bg-white rounded-2xl shadow-md border border-dashed border-gray-300 py-16 text-center">
                    <i class="fas fa-user-md text-5xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-semibold text-gray-700 mb-1">
                        Tidak ada dokter yang cocok dengan pencarian.
                    </p>
                    <p class="text-sm text-gray-500">
                        Coba hapus filter atau gunakan kata kunci lain.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($doctors as $doctor)
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100
                                    hover:shadow-lg hover:-translate-y-0.5 transition p-5 flex flex-col">
                            {{-- Header dokter --}}
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
                                    <i class="fas fa-user-md text-teal-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $doctor->user->name ?? 'Nama Dokter' }}
                                    </p>
                                    <p class="text-xs text-teal-600 font-semibold">
                                        {{ $doctor->poli->name ?? 'Poli Umum' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Deskripsi singkat --}}
                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">
                                {{ $doctor->about_me ?: 'Belum ada deskripsi dokter.' }}
                            </p>

                            {{-- Jadwal praktik --}}
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-600 mb-2 flex items-center gap-1">
                                    <i class="fas fa-calendar-alt text-teal-500"></i>
                                    Jadwal Praktik Mingguan
                                </p>

                                @if($doctor->schedules && $doctor->schedules->count())
                                    <div class="space-y-1.5 text-xs">
                                        @foreach($doctor->schedules as $schedule)
                                            <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-gray-50">
                                                <span class="font-medium text-gray-700">
                                                    {{ $schedule->day_of_week_name ?? ucfirst($schedule->day_of_week) }}
                                                </span>
                                                <span class="font-semibold text-teal-700">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                    –
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-gray-400 italic">
                                        Jadwal praktik belum tersedia.
                                    </p>
                                @endif
                            </div>

                            {{-- CTA login --}}
                            <div class="mt-4">
                                <a href="{{ route('login') }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg
                                          bg-teal-600 text-white text-xs font-semibold shadow hover:bg-teal-700">
                                    <i class="fas fa-calendar-plus text-xs"></i>
                                    Login untuk Membuat Janji
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginasi: hanya kalau objek punya method links() --}}
                @if(method_exists($doctors, 'links'))
                    <div class="mt-8">
                        {{ $doctors->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
