@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Transaksi & Jadwal</p>
            <h2 class="text-3xl font-bold text-gray-800">Manajemen Janji Temu (Appointment)</h2>
            <p class="text-sm text-gray-500">
                Kelola jadwal janji temu pasien dengan dokter di sistem rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.appointments.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
            + Buat Janji Temu
        </a>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Tanggal & Waktu</th>
                    <th class="px-6 py-3 text-left">Pasien</th>
                    <th class="px-6 py-3 text-left">Dokter</th>
                    <th class="px-6 py-3 text-left">Alasan / Keluhan</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        {{-- Tanggal & jam --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                @if($appointment->schedule)
                                    {{ substr($appointment->schedule->start_time,0,5) }}
                                    – {{ substr($appointment->schedule->end_time,0,5) }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>

                        {{-- Pasien --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">
                                {{ $appointment->patient->user->name ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                No. RM: {{ $appointment->patient->no_rm ?? '-' }}
                            </div>
                        </td>

                        {{-- Dokter --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">
                                {{ $appointment->doctor->user->name ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $appointment->doctor->poli->name ?? '-' }}
                            </div>
                        </td>

                        {{-- Keluhan singkat --}}
                        <td class="px-6 py-4 max-w-xs">
                            <p class="text-gray-700 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($appointment->complaint, 60) }}
                            </p>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $appointment->status;
                                $badge = match($status) {
                                    'pending'  => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'done'     => 'bg-sky-100 text-sky-800',
                                    default    => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ strtoupper($status) }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-3">

                                {{-- Detail (SHOW) --}}
                                <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                   class="text-emerald-700 hover:text-emerald-900">
                                    Detail
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.appointments.destroy', $appointment->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus janji temu ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="px-6 py-6 text-center text-sm text-gray-500">
                            Belum ada Janji Temu yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
