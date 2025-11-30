@extends('admin.layouts.app')

@section('page_title', 'Manajemen Data Poli')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Dashboard Admin
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-clinic-medical text-white/90"></i>
                Manajemen Data Poli
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Kelola data poli/klinik yang tersedia di rumah sakit.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/25 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>
                    Total poli: <span class="font-semibold">{{ $polis->count() }}</span>
                </span>
            </div>

            <a href="{{ route('admin.polis.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                      text-xs sm:text-sm font-semibold shadow hover:bg-teal-50 transition">
                <span class="mr-2 text-base leading-none">＋</span>
                Tambah Poli Baru
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div
            class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800
                   flex items-start gap-2 shadow-sm">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- CARD DAFTAR POLI --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">

        {{-- Header card --}}
        <div class="px-4 py-3 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fas fa-building-user text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-800">Daftar Poli / Klinik</h2>
                    <p class="text-[11px] text-gray-500">
                        Ikon poli, nama layanan, dan deskripsi singkat.
                    </p>
                </div>
            </div>

            <span class="text-[11px] text-gray-500 sm:text-right">
                Menampilkan <span class="font-semibold">{{ $polis->count() }}</span> poli yang tersedia.
            </span>
        </div>

        {{-- Isi tabel / kosong --}}
        @if ($polis->isEmpty())
            <div class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 mb-2">
                    <i class="fas fa-circle-exclamation text-gray-400"></i>
                </div>
                <p>Belum ada data poli yang tersedia.</p>
                <p class="text-[11px] text-gray-400 mt-1">
                    Tambahkan poli baru melalui tombol <span class="font-semibold">"Tambah Poli Baru"</span> di kanan atas.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm leading-normal">
                    <thead>
                        <tr class="bg-teal-50/70 border-b border-gray-100 text-[11px] uppercase tracking-wide text-gray-600">
                            <th class="px-6 py-2.5 text-left">ID</th>
                            <th class="px-6 py-2.5 text-left">Ikon</th>
                            <th class="px-6 py-2.5 text-left">Nama Poli</th>
                            <th class="px-6 py-2.5 text-left">Deskripsi</th>
                            <th class="px-6 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($polis as $poli)
                            <tr class="hover:bg-gray-50/70 transition">
                                {{-- ID --}}
                                <td class="px-6 py-3 text-gray-800 align-top">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-50 border border-gray-200 text-[11px] font-semibold">
                                        {{ $poli->id }}
                                    </span>
                                </td>

                                {{-- IKON --}}
                                <td class="px-6 py-3 text-gray-800 align-top">
                                    @if($poli->icon)
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-9 h-9 rounded-full bg-teal-50 flex items-center justify-center text-teal-700">
                                                <i class="fa-solid fa-{{ $poli->icon }} text-lg"></i>
                                            </span>
                                            <span class="text-[11px] text-gray-500">
                                                {{ $poli->icon }}
                                            </span>
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Tidak ada ikon</span>
                                    @endif
                                </td>

                                {{-- NAMA POLI --}}
                                <td class="px-6 py-3 align-top">
                                    <p class="font-semibold text-gray-900">
                                        {{ $poli->name }}
                                    </p>
                                </td>

                                {{-- DESKRIPSI --}}
                                <td class="px-6 py-3 text-gray-600 align-top">
                                    {{ \Illuminate\Support\Str::limit($poli->description ?: 'Tidak ada deskripsi', 80) }}
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-3 text-center align-top">
                                    <div class="inline-flex items-center space-x-3 text-[11px] sm:text-xs">
                                        <a href="{{ route('admin.polis.edit', $poli->id) }}"
                                           class="text-teal-700 hover:text-teal-900 font-semibold">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.polis.destroy', $poli->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus poli ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-800 font-semibold">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- fallback tambahan, meski sudah ditangani di atas --}}
                            <tr class="text-center">
                                <td colspan="5" class="px-6 py-6 text-gray-500 text-sm">
                                    Belum ada data Poli yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
