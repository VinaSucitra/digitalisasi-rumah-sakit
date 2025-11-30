@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Dashboard Admin</p>
            <h2 class="text-3xl font-bold text-gray-800">Manajemen Data Poli</h2>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data poli/klinik yang tersedia di rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.polis.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="mr-2 text-lg">＋</span> Tambah Poli Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
            Total Poli: {{ $polis->count() }}
        </span>
    </div>

    <!-- Tabel Daftar Poli -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-emerald-50">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-emerald-50 border-b border-emerald-100 text-gray-700 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Ikon</th>
                    <th class="px-6 py-3">Nama Poli</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($polis as $poli)
                    <tr class="hover:bg-emerald-50/40 transition duration-150 border-b border-gray-100">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $poli->id }}
                        </td>

                        {{-- Kolom Ikon --}}
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($poli->icon)
                                <span class="inline-flex items-center gap-2">
                                    <i class="fa-solid fa-{{ $poli->icon }} text-xl"></i>
                                    <span class="text-xs text-gray-500">
                                        {{ $poli->icon }}
                                    </span>
                                </span>
                            @else
                                <span class="text-xs text-gray-400">Tidak ada ikon</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            {{ $poli->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Illuminate\Support\Str::limit($poli->description ?: 'Tidak ada deskripsi', 60) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('admin.polis.edit', $poli->id) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs">
                                    Edit
                                </a>

                                <form action="{{ route('admin.polis.destroy', $poli->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus poli ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-semibold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="5" class="px-6 py-6 text-gray-500 text-sm">
                            Belum ada data Poli yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
