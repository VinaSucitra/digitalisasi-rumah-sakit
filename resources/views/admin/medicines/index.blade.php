@extends('admin.layouts.app')

@section('page_title', 'Manajemen Obat & Tindakan')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Manajemen Obat & Tindakan
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-prescription-bottle-medical text-white/90"></i>
                Daftar Obat & Tindakan
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Kelola data obat dan tindakan medis yang digunakan di rumah sakit.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/25 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Total item: <span class="font-semibold">{{ $medicines->total() }}</span></span>
            </div>

            <a href="{{ route('admin.medicines.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                      text-xs sm:text-sm font-semibold shadow hover:bg-teal-50 transition">
                <span class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                </span>
                Tambah Obat / Tindakan
            </a>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div
            class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800
                   flex items-start gap-2 shadow-sm">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- CARD DAFTAR OBAT & TINDAKAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header card --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fas fa-capsules text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Daftar Obat & Tindakan</h3>
                    <p class="text-[11px] text-gray-500">
                        Jenis item, tipe obat, stok, harga, dan deskripsi singkat.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 text-[10px] text-gray-500">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Obat
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-purple-50 text-purple-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Tindakan
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Stok tersedia
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-rose-50 text-rose-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Habis
                </span>
            </div>
        </div>

        @if ($medicines->isEmpty())
            {{-- Empty state --}}
            <div class="px-6 py-10 text-center text-sm text-gray-500 bg-gray-50">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                    <i class="fas fa-box-open text-gray-400"></i>
                </div>
                <p>Belum ada data obat / tindakan.</p>
                <p class="text-[11px] text-gray-400 mt-1">
                    Tambahkan data baru melalui tombol <span class="font-semibold">"Tambah Obat / Tindakan"</span> di atas.
                </p>
            </div>
        @else
            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-teal-50/70 border-b border-gray-100 text-[11px] uppercase tracking-wide text-gray-600">
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Jenis</th>
                            <th class="px-6 py-3 text-left">Tipe Obat</th>
                            <th class="px-6 py-3 text-left">Stok</th>
                            <th class="px-6 py-3 text-left">Harga</th>
                            <th class="px-6 py-3 text-left">Deskripsi</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($medicines as $medicine)
                            <tr class="hover:bg-gray-50/70 transition">
                                {{-- Nama --}}
                                <td class="px-6 py-3 font-semibold text-gray-900 align-top">
                                    {{ $medicine->name }}
                                </td>

                                {{-- Jenis: Obat / Tindakan --}}
                                <td class="px-6 py-3 align-top">
                                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold 
                                        {{ $medicine->type === 'Obat' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                        {{ $medicine->type }}
                                    </span>
                                </td>

                                {{-- Tipe Obat --}}
                                <td class="px-6 py-3 text-gray-700 align-top">
                                    @if($medicine->type === 'Obat' && $medicine->drug_type)
                                        <span class="inline-flex px-2 py-1 rounded-full text-[11px] font-medium
                                            {{ $medicine->drug_type === 'keras' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                            Obat {{ ucfirst($medicine->drug_type) }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Stok --}}
                                <td class="px-6 py-3 text-gray-900 align-top">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium">{{ $medicine->stock }}</span>
                                        @if($medicine->stock > 0)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700">
                                                Habis
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Harga --}}
                                <td class="px-6 py-3 text-gray-900 align-top whitespace-nowrap">
                                    Rp {{ number_format($medicine->price, 0, ',', '.') }}
                                </td>

                                {{-- Deskripsi --}}
                                <td class="px-6 py-3 text-gray-600 align-top">
                                    {{ \Illuminate\Support\Str::limit($medicine->description ?: 'Tidak ada deskripsi', 70) }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-3 text-center align-top">
                                    <div class="inline-flex justify-center space-x-4">
                                        <a href="{{ route('admin.medicines.edit', $medicine->id) }}"
                                           class="inline-flex items-center text-teal-700 hover:text-teal-900 text-[11px] font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15.232 5.232l3.536 3.536M4 13.5V19h5.5L19 9.5 13.5 4 4 13.5z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.medicines.destroy', $medicine->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                              class="inline-flex items-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center text-rose-600 hover:text-rose-800 text-[11px] font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">
                                    Belum ada data obat / tindakan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 py-4 bg-gray-50 border-t border-gray-100">
                {{ $medicines->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
