@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    {{-- Judul & deskripsi singkat (sama konsepnya dengan halaman Pasien) --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Manajemen Jadwal Dokter</h2>
            <p class="text-sm text-gray-600 mt-1">
                Kelola jadwal praktik dokter yang terdaftar di sistem rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.schedules.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-200">
            + Tambah Jadwal Baru
        </a>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div id="success-message"
             class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm"
             role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Kartu utama (dibuat sama pola-nya dengan kartu "Daftar Pasien") --}}
    <div class="bg-white shadow-xl rounded-xl overflow-hidden">

        {{-- Header kartu --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <h3 class="text-sm font-semibold text-gray-800">
                    Daftar Jadwal Dokter
                </h3>
            </div>
            <span class="text-xs text-gray-500">
                Total: {{ $schedules->total() }} jadwal
            </span>
        </div>

        {{-- Tabel jadwal --}}
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-600 uppercase border-b">
                    <th class="px-6 py-3 text-left font-semibold">No.</th>
                    <th class="px-6 py-3 text-left font-semibold">Dokter</th>
                    <th class="px-6 py-3 text-left font-semibold">Poli</th>
                    <th class="px-6 py-3 text-left font-semibold">Hari</th>
                    <th class="px-6 py-3 text-left font-semibold">Jam Mulai</th>
                    <th class="px-6 py-3 text-left font-semibold">Jam Selesai</th>
                    <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">
                        {{-- No. urut global (ikut pagination kalau ada) --}}
                        <td class="px-6 py-3 text-gray-700">
                            {{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}
                        </td>

                        {{-- Dokter --}}
                        <td class="px-6 py-3 text-gray-800">
                            {{ $schedule->doctor->user->name ?? '-' }}
                        </td>

                        {{-- Poli --}}
                        <td class="px-6 py-3 text-gray-800">
                            {{ $schedule->doctor->poli->name ?? $schedule->doctor->poli->nama_poli ?? '-' }}
                        </td>

                        {{-- Hari --}}
                        <td class="px-6 py-3 text-gray-800">
                            {{ $schedule->day_of_week }}
                        </td>

                        {{-- Jam Mulai & Selesai --}}
                        <td class="px-6 py-3 text-gray-800">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-3 text-gray-800">
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-3 text-gray-800">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                   class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold">
                                    Edit
                                </a>

                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-6 text-center text-sm text-gray-500">
                            Belum ada jadwal dokter yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination kalau ada --}}
        @if ($schedules->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // animasi hilang-notif sama seperti halaman lain
    document.addEventListener('DOMContentLoaded', () => {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease-out';
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 500);
            }, 5000);
        }
    });
</script>
@endsection
