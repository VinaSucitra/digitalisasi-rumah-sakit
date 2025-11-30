@extends('layouts.patient')

@section('title', 'Buat Janji Temu')
@section('page_title', 'Buat Janji Temu')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-teal-500
                rounded-2xl shadow-lg px-6 py-5 flex flex-col md:flex-row md:items-center
                md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-emerald-100 mb-1">
                Layanan Janji Temu
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-calendar-plus text-white/90"></i>
                Buat Janji Temu
            </h1>
            <p class="text-sm text-emerald-100 mt-1">
                Isi formulir berikut untuk mengajukan janji temu dengan dokter pilihan Anda.
            </p>
        </div>

        <a href="{{ route('patient.appointments.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                  text-sm font-semibold shadow-md hover:bg-emerald-50 transition">
            <i class="fas fa-list-ul mr-2 text-xs"></i>
            Lihat Daftar Janji
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 md:p-8 max-w-3xl">

        {{-- Notifikasi error --}}
        @if ($errors->any())
            <div class="mb-6 p-3 rounded-xl border border-rose-200 bg-rose-50 text-sm text-rose-700">
                <div class="font-semibold mb-1 flex items-center gap-2">
                    <i class="fas fa-circle-exclamation"></i>
                    Periksa kembali isian Anda:
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- DATA PASIEN --}}
            <div class="border-b border-gray-100 pb-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Informasi Pasien
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Nama Pasien
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-sm shadow-inner"
                               value="{{ auth()->user()->name }}"
                               readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Tanggal Booking
                        </label>
                        <input type="date"
                               name="booking_date"
                               value="{{ old('booking_date') }}"
                               class="w-full px-3 py-2 rounded-lg border text-sm shadow-sm
                                      border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                      @error('booking_date') border-rose-500 @enderror"
                               required>
                    </div>
                </div>
            </div>

            {{-- DETAIL JANJI TEMU --}}
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-gray-800 mb-1 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Detail Janji Temu
                </h2>

                {{-- PILIH POLI --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Poli</label>
                    <select id="poli_id"
                            name="poli_id"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                   focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('poli_id') border-rose-500 @enderror"
                            required>
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}"
                                {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PILIH DOKTER (AKAN TERFILTER OLEH POLI) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Dokter</label>
                    <select id="doctor_id"
                            name="doctor_id"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                   focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('doctor_id') border-rose-500 @enderror"
                            required>
                        <option value="">-- Pilih Dokter --</option>
                        {{-- opsi dokter akan diisi via JavaScript berdasarkan poli --}}
                    </select>
                    <p class="mt-1 text-[11px] text-gray-500">
                        Dokter yang tampil akan otomatis disaring berdasarkan poli yang Anda pilih.
                    </p>
                </div>

                {{-- PILIH JADWAL --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Pilih Jadwal Konsultasi
                    </label>
                    <select id="schedule_id"
                            name="schedule_id"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                   focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('schedule_id') border-rose-500 @enderror"
                            required>
                        <option value="">-- Pilih Slot Jadwal --</option>
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}"
                                {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ ucfirst($schedule->day_of_week) }}
                                {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                | {{ $schedule->doctor->user->name }}
                                @if($schedule->doctor->poli)
                                    ({{ $schedule->doctor->poli->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-[11px] text-gray-500">
                        Setiap slot konsultasi berdurasi 30 menit.
                    </p>
                </div>

                {{-- KELUHAN --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Keluhan Singkat</label>
                    <textarea name="complaint"
                              rows="4"
                              class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm shadow-sm
                                     focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                     @error('complaint') border-rose-500 @enderror"
                              placeholder="Contoh: Demam sejak 3 hari, batuk kering, pusing..."
                              required>{{ old('complaint') }}</textarea>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('patient.appointments.index') }}"
                   class="px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold
                               hover:bg-teal-700 shadow-md">
                    Kirim Permohonan Janji Temu
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ====================== JS: FILTER DOKTER BERDASARKAN POLI ====================== --}}
@php
    // template URL untuk AJAX
    $ajaxUrlTemplate = route('patient.appointments.byPoli', ['poli' => '__POLI_ID__']);
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    const poliSelect   = document.getElementById('poli_id');
    const doctorSelect = document.getElementById('doctor_id');
    const oldDoctorId  = '{{ old('doctor_id') }}';
    const ajaxUrlTpl   = @json($ajaxUrlTemplate);

    function loadDoctorsByPoli(poliId, selectedId = null) {
        if (!poliId) {
            doctorSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
            return;
        }

        doctorSelect.innerHTML = '<option value="">Memuat dokter...</option>';

        const url = ajaxUrlTpl.replace('__POLI_ID__', poliId);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Pilih Dokter --</option>';

                data.forEach(function (doctor) {
                    const selected = (String(doctor.id) === String(selectedId)) ? 'selected' : '';
                    const poliName = doctor.poli_name ? ' (' + doctor.poli_name + ')' : '';
                    options += `<option value="${doctor.id}" ${selected}>${doctor.name}${poliName}</option>`;
                });

                doctorSelect.innerHTML = options;
            })
            .catch(() => {
                doctorSelect.innerHTML = '<option value="">Gagal memuat dokter</option>';
            });
    }

    // ketika poli berubah
    poliSelect.addEventListener('change', function () {
        loadDoctorsByPoli(this.value, null);
    });

    // jika form kembali dari validasi & sudah ada poli dipilih => langsung load
    if (poliSelect.value) {
        loadDoctorsByPoli(poliSelect.value, oldDoctorId || null);
    }
});
</script>
@endsection
