@extends('layouts.doctor')

@section('page_title', 'Jadwal Saya')

@section('content')

<div class="space-y-8">

    {{-- HERO HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-500 to-emerald-500 rounded-2xl px-6 py-5 shadow-md text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-teal-100 mb-1">Jadwal Praktik</p>
            <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-2">
                <i class="fas fa-clock text-white/90"></i>
                Jadwal Praktik Saya
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Atur sendiri kapan Anda tersedia untuk praktik. Durasi setiap slot adalah
                <span class="font-semibold">30 menit</span>.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2 text-xs">
            <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2">
                <p class="text-[10px] uppercase tracking-widest text-teal-100">Hari Ini</p>
                <p class="font-semibold text-sm">
                    {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                </p>
            </div>

            <a href="{{ route('doctor.schedules.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-full bg-white text-teal-700 text-sm font-semibold shadow hover:bg-teal-50 transition">
                <i class="fas fa-plus-circle mr-2"></i>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2"
             role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2"
             role="alert">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- KARTU JADWAL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-teal-50 p-6 space-y-4">

        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-sm font-semibold text-teal-900 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-teal-100 text-teal-700">
                        <i class="fas fa-calendar-day text-[11px]"></i>
                    </span>
                    Daftar Jadwal Praktik
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Pastikan tidak ada jadwal yang saling tumpang tindih pada hari dan jam yang sama.
                </p>
            </div>

            @if($schedules->count() > 0)
                <span class="text-[11px] px-3 py-1 rounded-full bg-teal-50 text-teal-700 font-medium">
                    Total Jadwal: {{ $schedules->count() }}
                </span>
            @endif
        </div>

        @if($schedules->isEmpty())
            <div
                class="border border-dashed border-teal-200 rounded-xl px-4 py-6 text-center text-sm text-teal-700 bg-teal-50/40 flex flex-col items-center gap-2">
                <i class="fas fa-calendar-plus text-2xl text-teal-400"></i>
                <p>Anda belum memiliki jadwal praktik.</p>
                <p class="text-xs text-teal-500">
                    Klik tombol <span class="font-semibold">“Tambah Jadwal”</span> di kanan atas untuk membuat jadwal
                    baru.
                </p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full text-xs">
                    <thead class="bg-teal-50/70">
                        <tr class="text-[11px] uppercase tracking-wide text-gray-600">
                            <th class="py-3 px-4 text-left font-semibold">No</th>
                            <th class="py-3 px-4 text-left font-semibold">Hari</th>
                            <th class="py-3 px-4 text-left font-semibold">Jam Mulai</th>
                            <th class="py-3 px-4 text-left font-semibold">Jam Selesai</th>
                            <th class="py-3 px-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($schedules as $index => $schedule)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- No --}}
                                <td class="py-3 px-4 text-gray-700">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Hari --}}
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center gap-2">
                                        <span
                                            class="w-7 h-7 rounded-full bg-teal-50 flex items-center justify-center text-teal-600">
                                            <i class="fas fa-sun text-[11px]"></i>
                                        </span>
                                        <span class="font-semibold text-gray-800">
                                            {{ ucfirst($schedule->day_of_week) }}
                                        </span>
                                    </span>
                                </td>

                                {{-- Jam Mulai --}}
                                <td class="py-3 px-4 text-gray-800">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} WIB
                                </td>

                                {{-- Jam Selesai --}}
                                <td class="py-3 px-4 text-gray-800">
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-3 text-xs">
                                        <a href="{{ route('doctor.schedules.edit', $schedule->id) }}"
                                           class="text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-pen"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('doctor.schedules.destroy', $schedule->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1">
                                                <i class="fas fa-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection
