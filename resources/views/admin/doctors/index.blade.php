@extends('admin.layouts.app')

@section('page_title', 'Manajemen Dokter')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header + tombol tambah --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-teal-900">Manajemen Dokter</h1>
            <p class="text-sm text-gray-500">Kelola data akun dan profil dokter di rumah sakit.</p>
        </div>

        <a href="{{ route('admin.doctors.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-teal-600 text-white text-sm font-semibold shadow hover:bg-teal-700 transition">
            <i class="fas fa-user-plus mr-2 text-xs"></i>
            Tambah Dokter Baru
        </a>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 flex items-start gap-2">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabel dokters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                Daftar Dokter
            </h2>
            <span class="text-[11px] text-gray-500">
                Total: {{ $doctors->count() }} dokter
            </span>
        </div>

        @if($doctors->isEmpty())
            <div class="px-4 py-6 text-sm text-gray-500">
                Belum ada dokter terdaftar.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 border-b">
                            <th class="text-left py-2 px-4">Nama</th>
                            <th class="text-left py-2 px-4">Poli Spesialis</th>
                            <th class="text-left py-2 px-4">No. SIP</th>
                            <th class="text-left py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($doctors as $doctor)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4">
                                    {{ $doctor->user->name ?? '-' }}
                                    <div class="text-[11px] text-gray-500">
                                        {{ $doctor->user->email ?? '' }}
                                    </div>
                                </td>
                                <td class="py-2 px-4">
                                    {{ $doctor->poli->name ?? '-' }}
                                </td>
                                <td class="py-2 px-4">
                                    {{ $doctor->sip ?? '-' }}
                                </td>
                                <td class="py-2 px-4">
                                    <div class="flex items-center gap-3 text-xs">
                                        {{-- PENTING: pakai user_id, bukan id doctor_details --}}
                                        <a href="{{ route('admin.doctors.edit', $doctor->user->id) }}"
                                           class="text-teal-700 hover:text-teal-900 font-semibold flex items-center gap-1">
                                            <i class="fas fa-pen-to-square"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.doctors.destroy', $doctor->user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1">
                                                <i class="fas fa-trash-can"></i> Hapus
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
