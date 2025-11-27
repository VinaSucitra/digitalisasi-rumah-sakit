@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Manajemen Obat & Tindakan</p>
            <h2 class="text-3xl font-bold text-gray-800">Daftar Obat & Tindakan</h2>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data obat dan tindakan medis yang digunakan di rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.medicines.create') }}"
           class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
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

    {{-- Alert --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Card daftar --}}
    <div class="bg-emerald-50/60 rounded-2xl shadow-inner border border-emerald-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-100">
            <div class="flex items-center space-x-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <h3 class="text-base font-semibold text-gray-800">
                    Daftar Obat & Tindakan
                </h3>
            </div>

            <p class="text-xs text-gray-500">
                Total: {{ $medicines->count() }} item
            </p>
        </div>

        <div class="bg-white m-3 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Jenis</th>
                        <th class="px-6 py-3 text-left">Harga</th>
                        <th class="px-6 py-3 text-left">Deskripsi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($medicines as $medicine)
                        <tr class="hover:bg-emerald-50/40 transition duration-150 border-b border-gray-100">
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                                {{ $medicine->name }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ $medicine->type === 'Obat' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $medicine->type }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-900">
                                Rp {{ number_format($medicine->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit($medicine->description ?: 'Tidak ada deskripsi', 60) }}
                            </td>
                            <td class="px-6 py-3 text-sm text-center">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('admin.medicines.edit', $medicine->id) }}"
                                       class="inline-flex items-center text-emerald-700 hover:text-emerald-900 text-xs font-semibold">
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
                                                class="inline-flex items-center text-red-600 hover:text-red-800 text-xs font-semibold">
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
                            <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                Belum ada data obat / tindakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
