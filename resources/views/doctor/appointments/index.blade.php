@extends('layouts.doctor')

@section('page_title', 'Janji Temu Saya')

@section('content')

<div class="space-y-6">

    {{-- HEADER HALAMAN --}}
    <div class="flex items-center justify-between border-b pb-3 border-teal-200">
        <h1 class="text-xl font-bold text-teal-900 flex items-center gap-2">
            <i class="fas fa-calendar-check text-teal-600"></i>
            Daftar Janji Temu Saya
        </h1>
        {{-- Tombol aksi cepat jika ada --}}
    </div>

    {{-- MESSAGE NOTIFIKASI (SUCCESS/ERROR) --}}
    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg relative text-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-3 rounded-lg relative text-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER TAB / STATS RINGKAS --}}
    <div class="flex flex-wrap gap-4 text-sm font-medium border-b border-gray-200">
        <a href="{{ route('doctor.appointments.index') }}" 
           class="pb-2 px-3 {{ !request()->get('status') ? 'border-b-2 border-teal-600 text-teal-700' : 'text-gray-500 hover:text-teal-600' }}">
            Semua Janji Temu
        </a>
        <a href="{{ route('doctor.appointments.index', ['status' => 'Pending']) }}" 
           class="pb-2 px-3 {{ request()->get('status') === 'Pending' ? 'border-b-2 border-rose-600 text-rose-700' : 'text-gray-500 hover:text-rose-600' }}">
            Menunggu Validasi ({{ $appointments->where('status', 'Pending')->count() }})
        </a>
        <a href="{{ route('doctor.appointments.index', ['status' => 'Approved']) }}" 
           class="pb-2 px-3 {{ request()->get('status') === 'Approved' ? 'border-b-2 border-emerald-600 text-emerald-700' : 'text-gray-500 hover:text-emerald-600' }}">
            Sudah Disetujui ({{ $appointments->where('status', 'Approved')->count() }})
        </a>
    </div>

    {{-- TABEL DATA JANJI TEMU --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead class="bg-teal-50/50">
                    <tr class="text-[11px] uppercase tracking-wide text-gray-600 border-b border-teal-100">
                        <th class="py-3 px-4 text-left">Pasien</th>
                        <th class="py-3 px-4 text-left">Tanggal & Jam</th>
                        <th class="py-3 px-4 text-left">Keluhan Singkat</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $filteredAppointments = $appointments;
                        if (request()->get('status')) {
                            $filteredAppointments = $appointments->where('status', request()->get('status'));
                        }
                    @endphp

                    @forelse($filteredAppointments as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold text-gray-800">
                                {{ $app->patient->user->name ?? 'Pasien Tidak Dikenal' }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($app->appointment_date)->format('d M Y') }}
                                @if($app->schedule)
                                    <span class="block text-[11px] text-teal-600 font-medium">
                                        ({{ date('H:i', strtotime($app->schedule->start_time)) }} WIB)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500">
                                {{ \Illuminate\Support\Str::limit($app->complaint, 50) }}
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $status = $app->status ?? 'pending';
                                    $color = match($status) {
                                        'Approved' => 'bg-emerald-100 text-emerald-700',
                                        'Rejected' => 'bg-rose-100 text-rose-700',
                                        'Selesai'  => 'bg-sky-100 text-sky-700',
                                        default    => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-[11px] font-semibold {{ $color }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center space-x-2">
                                @if($app->status === 'Pending')
                                    {{-- Aksi Validasi (Approve/Reject) --}}
                                    <form action="{{ route('doctor.appointments.approve', $app->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 transition text-sm font-semibold">
                                            <i class="fas fa-check-circle mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('doctor.appointments.reject', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin menolak janji temu ini?');">
                                        @csrf
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 transition text-sm font-semibold">
                                            <i class="fas fa-times-circle mr-1"></i> Reject
                                        </button>
                                    </form>
                                @elseif($app->status === 'Approved')
                                    {{-- Aksi Lanjut ke Rekam Medis --}}
                                    <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $app->id]) }}"
                                       class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-teal-600 text-white hover:bg-teal-700 transition">
                                        <i class="fas fa-notes-medical mr-1"></i> Mulai Pemeriksaan
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Selesai/Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                Tidak ada janji temu yang ditemukan untuk Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection