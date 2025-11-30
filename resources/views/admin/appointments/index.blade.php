@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Jadwal</p>
            <h2 class="text-3xl font-bold text-gray-800">Verifikasi Janji Temu (Appointment)</h2>
            <p class="text-sm text-gray-500">
                Admin dapat melihat dan memverifikasi (approve / reject) janji temu pasien dengan dokter.
            </p>
        </div>

        {{-- Filter status opsional --}}
        <form method="GET" class="flex items-center space-x-2">
            <label for="status" class="text-xs text-gray-500">Filter status:</label>
            <select name="status" id="status"
                    onchange="this.form.submit()"
                    class="text-xs px-2 py-1 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                <option value="" {{ request('status') === null ? 'selected' : '' }}>Semua</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Selesai</option>
            </select>
        </form>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert error --}}
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
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
                    <th class="px-6 py-3 text-center">Verifikasi</th>
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

                            @if($appointment->status === 'rejected' && $appointment->reject_reason)
                                <div class="mt-1 text-xs text-gray-500">
                                    Alasan: {{ $appointment->reject_reason }}
                                </div>
                            @endif
                        </td>

                        {{-- Verifikasi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if($appointment->status === 'pending')
                                <div class="flex flex-col items-center space-y-2">

                                    {{-- APPROVE --}}
                                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}"
                                          method="POST"
                                          class="inline-flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-600 text-white hover:bg-emerald-700">
                                            Approve
                                        </button>
                                    </form>

                                    {{-- REJECT --}}
                                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}"
                                          method="POST"
                                          class="inline-flex flex-col items-center space-y-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <input type="text"
                                               name="reject_reason"
                                               placeholder="Alasan (opsional)"
                                               class="px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 w-40">
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white hover:bg-red-700">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">
                                    Tidak ada aksi
                                </span>
                            @endif
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
