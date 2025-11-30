@extends('admin.layouts.app')

@section('page_title', 'Verifikasi Janji Temu')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Jadwal
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-calendar-check text-white/90"></i>
                Verifikasi Janji Temu (Appointment)
            </h1>
            <p class="text-sm text-teal-100 mt-1 max-w-xl">
                Admin dapat melihat dan memverifikasi (<span class="font-semibold">approve / reject</span>) janji temu pasien dengan dokter.
            </p>
        </div>

        {{-- Filter status --}}
        <form method="GET"
              class="bg-white/10 backdrop-blur px-3 py-2 rounded-xl flex items-center gap-2 text-xs">
            <label for="status" class="text-teal-50 whitespace-nowrap">
                Filter status
            </label>
            <select name="status" id="status"
                    onchange="this.form.submit()"
                    class="text-[11px] px-2 py-1 rounded-lg border border-white/40 bg-white/90 text-teal-800
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="" {{ request('status') === null ? 'selected' : '' }}>Semua</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Selesai</option>
            </select>
        </form>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div
            class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800
                   flex items-start gap-2 shadow-sm">
            <i class="fas fa-check-circle mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div
            class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800
                   flex items-start gap-2 shadow-sm">
            <i class="fas fa-circle-exclamation mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- CARD TABEL APPOINTMENT --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header kecil --}}
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fas fa-clipboard-list text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-800">
                        Daftar Janji Temu
                    </h2>
                    <p class="text-[11px] text-gray-500">
                        Tanggal, pasien, dokter, keluhan, status, dan aksi verifikasi.
                    </p>
                </div>
            </div>

            <span class="hidden sm:inline-flex items-center gap-1 text-[11px] text-gray-500">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Pending
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 ml-3"></span> Approved
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 ml-3"></span> Rejected
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-teal-50/70">
                    <tr class="text-[11px] font-semibold text-gray-600 uppercase tracking-wide">
                        <th class="px-6 py-3 text-left">Tanggal & Waktu</th>
                        <th class="px-6 py-3 text-left">Pasien</th>
                        <th class="px-6 py-3 text-left">Dokter</th>
                        <th class="px-6 py-3 text-left">Alasan / Keluhan</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-center">Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50/80 transition">
                            {{-- Tanggal & jam --}}
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                <div class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">
                                    @if($appointment->schedule)
                                        {{ substr($appointment->schedule->start_time,0,5) }}
                                        – {{ substr($appointment->schedule->end_time,0,5) }}
                                    @else
                                        <span class="italic text-gray-400">Belum dijadwalkan</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Pasien --}}
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                <div class="font-semibold text-gray-900">
                                    {{ $appointment->patient->user->name ?? '-' }}
                                </div>
                                <div class="text-[11px] text-gray-500">
                                    No. RM: {{ $appointment->patient->no_rm ?? '-' }}
                                </div>
                            </td>

                            {{-- Dokter --}}
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                <div class="font-semibold text-gray-900">
                                    {{ $appointment->doctor->user->name ?? '-' }}
                                </div>
                                <div class="text-[11px] text-gray-500">
                                    {{ $appointment->doctor->poli->name ?? '-' }}
                                </div>
                            </td>

                            {{-- Keluhan singkat --}}
                            <td class="px-6 py-4 align-top max-w-xs">
                                <p class="text-gray-700 text-xs sm:text-sm line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($appointment->complaint, 80) }}
                                </p>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                @php
                                    $status = $appointment->status;
                                    $badge = match($status) {
                                        'pending'  => 'bg-amber-100 text-amber-800',
                                        'approved' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-rose-100 text-rose-700',
                                        'done'     => 'bg-sky-100 text-sky-800',
                                        default    => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $badge }}">
                                    {{ strtoupper($status) }}
                                </span>

                                @if($appointment->status === 'rejected' && $appointment->reject_reason)
                                    <div class="mt-1 text-[11px] text-gray-500">
                                        Alasan: {{ $appointment->reject_reason }}
                                    </div>
                                @endif
                            </td>

                            {{-- Verifikasi --}}
                            <td class="px-6 py-4 align-top whitespace-nowrap text-center text-sm font-medium">
                                @if($appointment->status === 'pending')
                                    <div class="flex flex-col items-center gap-2">

                                        {{-- APPROVE --}}
                                        <form action="{{ route('admin.appointments.update-status', $appointment->id) }}"
                                              method="POST"
                                              class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-[11px] font-semibold rounded-full
                                                           bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm">
                                                Approve
                                            </button>
                                        </form>

                                        {{-- REJECT --}}
                                        <form action="{{ route('admin.appointments.update-status', $appointment->id) }}"
                                              method="POST"
                                              class="inline-flex flex-col items-center gap-1 w-full">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <input type="text"
                                                   name="reject_reason"
                                                   placeholder="Alasan (opsional)"
                                                   class="px-2 py-1 text-[11px] border border-gray-300 rounded-lg
                                                          focus:ring-emerald-500 focus:border-emerald-500 w-40">
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-[11px] font-semibold rounded-full
                                                           bg-rose-600 text-white hover:bg-rose-700 shadow-sm">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[11px] text-gray-400">
                                        Tidak ada aksi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 bg-gray-50">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <i class="fas fa-calendar-xmark text-gray-400"></i>
                                </div>
                                <p>Belum ada Janji Temu yang tercatat.</p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    Janji temu baru pasien akan muncul di sini untuk diverifikasi.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
