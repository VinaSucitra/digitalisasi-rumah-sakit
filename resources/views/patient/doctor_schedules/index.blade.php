@extends('layouts.patient')

@section('title', 'Jadwal Dokter')
@section('page_title', 'Jadwal Dokter')

@section('content')
    <div class="bg-white shadow-xl rounded-xl p-6 md:p-8 max-w-3xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Jadwal Dokter</h2>
        <p class="text-gray-600 mb-6">Berikut adalah jadwal dokter yang tersedia.</p>

        @if($doctorDetails->isEmpty())
            <p class="text-gray-500">Tidak ada jadwal dokter yang tersedia.</p>
        @else
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-teal-100">
                        <th class="px-4 py-2 text-left">Dokter</th>
                        <th class="px-4 py-2 text-left">Poli</th>
                        <th class="px-4 py-2 text-left">Jadwal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctorDetails as $doctor)
                        @foreach($doctor->schedules as $schedule)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $doctor->user->name }}</td>
                                <td class="px-4 py-2">{{ $doctor->poli->name }}</td>
                                <td class="px-4 py-2">{{ $schedule->date }} - {{ $schedule->time }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
