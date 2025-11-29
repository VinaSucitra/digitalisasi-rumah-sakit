@extends('layouts.patient')

@section('title', 'Jadwal Dokter')
@section('page_title', 'Jadwal Dokter')

@section('content')
    <div class="bg-white shadow-xl rounded-xl p-6 md:p-8 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Jadwal Dokter</h2>
        <p class="text-gray-600 mb-6">Berikut adalah jadwal dokter yang tersedia.</p>

        @if($doctorDetails->isEmpty())
            <p class="text-gray-500">Tidak ada dokter yang terdaftar atau memiliki jadwal.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-teal-600 text-white text-sm">
                            <th class="px-4 py-3 text-left w-1/4 rounded-tl-xl">Dokter</th>
                            <th class="px-4 py-3 text-left w-1/4">Poli</th>
                            <th class="px-4 py-3 text-left w-2/4 rounded-tr-xl">Jadwal Praktik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctorDetails as $doctor)
                            @php
                                // Ambil dan urutkan jadwal berdasarkan hari
                                $sortedSchedules = $doctor->schedules->sortBy(function($schedule) {
                                    $daysOrder = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 7];
                                    return $daysOrder[strtolower($schedule->day_of_week)] ?? 99;
                                });

                                // Format semua jadwal menjadi satu string HTML
                                $scheduleList = $sortedSchedules->map(function($schedule) {
                                    $day = ucfirst($schedule->day_of_week);
                                    $start = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                                    $end = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
                                    return "<strong>{$day}</strong>: {$start} - {$end}";
                                })->implode('<br>'); // Gabungkan dengan baris baru HTML
                            @endphp

                            <tr class="border-b border-gray-200 hover:bg-teal-50/50">
                                <td class="px-4 py-3 text-gray-700 font-semibold">{{ $doctor->user->name ?? 'Dokter Dihapus' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $doctor->poli->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    @if ($scheduleList)
                                        {{-- Menampilkan Jadwal yang sudah diformat --}}
                                        {!! $scheduleList !!} 
                                    @else
                                        <span class="text-gray-500 italic">Tidak ada jadwal aktif.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        @endif
    </div>
@endsection