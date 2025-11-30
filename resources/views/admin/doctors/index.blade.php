@extends('admin.layouts.app')

@section('page_title', 'Manajemen Dokter')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Manajemen Data • Dokter
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-user-doctor text-white/90"></i>
                Manajemen Dokter
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Kelola data akun dan profil dokter di rumah sakit Anda.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/25 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>
                    Total dokter: <span class="font-semibold">{{ $doctors->count() }}</span>
                </span>
            </div>

            <a href="{{ route('admin.doctors.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white text-teal-700
                      text-xs sm:text-sm font-semibold shadow hover:bg-teal-50 transition">
                <i class="fas fa-user-plus mr-2 text-xs"></i>
                Tambah Dokter Baru
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

    {{-- CARD DAFTAR DOKTER --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header card --}}
        <div class="px-4 py-3 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fas fa-users-medical text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-800">Daftar Dokter</h2>
                    <p class="text-[11px] text-gray-500">
                        Data dokter aktif beserta poli dan nomor SIP.
                    </p>
                </div>
            </div>

            <span class="text-[11px] text-gray-500 sm:text-right">
                Menampilkan <span class="font-semibold">{{ $doctors->count() }}</span> dokter terdaftar.
            </span>
        </div>

        {{-- Isi tabel / kosong --}}
        @if($doctors->isEmpty())
            <div class="px-4 py-8 text-center text-sm text-gray-500 bg-gray-50">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 mb-2">
                    <i class="fas fa-user-slash text-gray-400"></i>
                </div>
                <p>Belum ada dokter terdaftar.</p>
                <p class="text-[11px] text-gray-400 mt-1">
                    Gunakan tombol <span class="font-semibold">"Tambah Dokter Baru"</span> di kanan atas untuk menambahkan.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-teal-50/60 text-[11px] uppercase tracking-wide text-gray-500 border-b border-gray-100">
                            <th class="text-left py-2.5 px-4">Nama</th>
                            <th class="text-left py-2.5 px-4">Poli Spesialis</th>
                            <th class="text-left py-2.5 px-4">No. SIP</th>
                            <th class="text-left py-2.5 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($doctors as $doctor)
                            <tr class="hover:bg-gray-50/70">
                                {{-- Nama + email --}}
                                <td class="py-3 px-4 align-top">
                                    <div class="font-semibold text-gray-800 flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-teal-50 text-teal-700 text-xs">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                        <span>{{ $doctor->user->name ?? '-' }}</span>
                                    </div>
                                    <div class="text-[11px] text-gray-500 pl-9">
                                        {{ $doctor->user->email ?? '' }}
                                    </div>
                                </td>

                                {{-- Poli --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-teal-50 text-[11px] font-medium text-teal-700">
                                        {{ $doctor->poli->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- SIP --}}
                                <td class="py-3 px-4 align-top">
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $doctor->sip ?? '-' }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3 px-4 align-top">
                                    <div class="flex items-center gap-3 text-[11px] sm:text-xs">

                                        {{-- EDIT: pakai user_id tetap sama --}}
                                        <a href="{{ route('admin.doctors.edit', $doctor->user->id) }}"
                                           class="inline-flex items-center gap-1 text-teal-700 hover:text-teal-900 font-semibold">
                                            <i class="fas fa-pen-to-square"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.doctors.destroy', $doctor->user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
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
