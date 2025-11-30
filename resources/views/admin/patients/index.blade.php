@extends('admin.layouts.app')

@section('page_title', 'Manajemen Pasien')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Manajemen Data • Pasien
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-hospital-user text-white/90"></i>
                Manajemen Pasien
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Kelola data pasien yang terdaftar di sistem rumah sakit.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/25 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>
                    Total pasien: <span class="font-semibold">{{ $patients->count() }}</span>
                </span>
            </div>

            <a href="{{ route('admin.patients.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white text-teal-700
                      text-xs sm:text-sm font-semibold shadow hover:bg-teal-50 transition">
                <i class="fas fa-user-plus mr-2 text-xs"></i>
                Tambah Pasien Baru
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div
            class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800
                   flex items-start gap-2 shadow-sm">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- CARD DAFTAR PASIEN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header card --}}
        <div class="px-4 py-3 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-800">Daftar Pasien</h2>
                    <p class="text-[11px] text-gray-500">
                        Data pasien aktif beserta kontak dan alamat.
                    </p>
                </div>
            </div>

            <span class="text-[11px] text-gray-500 sm:text-right">
                Menampilkan <span class="font-semibold">{{ $patients->count() }}</span> pasien terdaftar.
            </span>
        </div>

        {{-- Isi tabel / kosong --}}
        @if($patients->isEmpty())
            <div class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 mb-2">
                    <i class="fas fa-user-slash text-gray-400"></i>
                </div>
                <p>Belum ada pasien terdaftar.</p>
                <p class="text-[11px] text-gray-400 mt-1">
                    Gunakan tombol <span class="font-semibold">"Tambah Pasien Baru"</span> di kanan atas untuk menambahkan.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-teal-50/60 text-[11px] uppercase tracking-wide text-gray-500 border-b border-gray-100">
                            <th class="text-left py-2.5 px-4">No. RM</th>
                            <th class="text-left py-2.5 px-4">Nama</th>
                            <th class="text-left py-2.5 px-4">Email</th>
                            <th class="text-left py-2.5 px-4">JK</th>
                            <th class="text-left py-2.5 px-4">Tgl Lahir</th>
                            <th class="text-left py-2.5 px-4">No. HP</th>
                            <th class="text-left py-2.5 px-4">Alamat</th>
                            <th class="text-left py-2.5 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($patients as $user)
                            @php
                                $detail = $user->patient; // relasi ke model Patient
                            @endphp
                            <tr class="hover:bg-gray-50/70">
                                {{-- No RM --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-50 border border-gray-200 font-mono text-[11px]">
                                        {{ $detail->no_rm ?? '-' }}
                                    </span>
                                </td>

                                {{-- Nama --}}
                                <td class="py-3 px-4 align-top">
                                    <p class="font-semibold text-gray-800">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-[11px] text-gray-500">
                                        ID User: {{ $user->id }}
                                    </p>
                                </td>

                                {{-- Email --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="text-xs text-gray-700">
                                        {{ $user->email }}
                                    </span>
                                </td>

                                {{-- Jenis kelamin --}}
                                <td class="py-3 px-4 align-top">
                                    @if(optional($detail)->gender === 'L')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-sky-50 text-[11px] text-sky-700 font-medium">
                                            <i class="fas fa-mars mr-1"></i>Laki-laki
                                        </span>
                                    @elseif(optional($detail)->gender === 'P')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-rose-50 text-[11px] text-rose-700 font-medium">
                                            <i class="fas fa-venus mr-1"></i>Perempuan
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Tgl lahir --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="text-xs text-gray-700">
                                        {{ optional($detail)->birth_date ?? '-' }}
                                    </span>
                                </td>

                                {{-- No HP --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="text-xs text-gray-700">
                                        {{ optional($detail)->phone ?? '-' }}
                                    </span>
                                </td>

                                {{-- Alamat --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="block max-w-xs truncate text-xs text-gray-700"
                                          title="{{ optional($detail)->address }}">
                                        {{ optional($detail)->address ?? '-' }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3 px-4 align-top">
                                    <div class="flex items-center gap-3 text-[11px] sm:text-xs">
                                        <a href="{{ route('admin.patients.edit', $user->id) }}"
                                           class="inline-flex items-center gap-1 text-teal-700 hover:text-teal-900 font-semibold">
                                            <i class="fas fa-pen-to-square"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.patients.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus pasien ini? Semua data terkait bisa ikut terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-rose-600 hover:text-rose-800 font-semibold">
                                                <i class="fas fa-trash-can"></i>
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
