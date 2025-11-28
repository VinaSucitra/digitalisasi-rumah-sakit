@extends('layouts.patient')

@section('title', 'Jadwal Praktik Dokter')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <h2 class="text-3xl font-extrabold mb-8 text-teal-800 border-b pb-2">
        <i class="fas fa-calendar-alt mr-2"></i> Jadwal Praktik Dokter
    </h2>

    <p class="mb-6 text-gray-600">
        Daftar dokter spesialis beserta jadwal praktik mingguan mereka.
    </p>

    @if ($doctors->isEmpty())
        <div class="p-5 bg-gray-50 border border-gray-200 text-gray-700 rounded-lg shadow-sm">
            Tidak ada data dokter dan jadwal yang tersedia saat ini.
        </div>
    @endif

    {{-- Looping melalui setiap DoctorDetail (dikirim sebagai $doctor) --}}
    @foreach ($doctors as $doctor)
        <div class="bg-white shadow-lg rounded-xl mb-6 p-6 border-t-4 border-teal-500">
            <div class="flex items-center space-x-4">
                <img class="h-16 w-16 rounded-full object-cover bg-gray-200" 
                     src="https://ui-avatars.com/api/?name={{ urlencode($doctor->user->name ?? 'Dr') }}&background=10b981&color=fff&size=64" 
                     alt="{{ $doctor->user->name ?? 'Dokter' }}">
                
                <div>
                    {{-- Akses Nama dari relasi User --}}
                    <h3 class="text-xl font-bold text-gray-800">{{ $doctor->user->name ?? 'Nama Dokter Tidak Diketahui' }}</h3>
                    
                    {{-- Akses Nama Poli dari relasi Poli --}}
                    <p class="text-teal-600 font-semibold">{{ $doctor->poli->name ?? 'Poli Umum' }}</p>
                    
                    <p class="text-sm text-gray-500 mt-1">
                        Spesialisasi: **{{ $doctor->specialty ?? 'Belum Ditentukan' }}**
                    </p>
                </div>
            </div>

            <h4 class="text-lg font-semibold mt-6 mb-3 text-gray-700 border-b pb-1">Jadwal Praktik Mingguan</h4>
            
            {{-- Jadwal diambil langsung dari relasi schedules --}}
            @if ($doctor->schedules->isNotEmpty())
                <ul class="space-y-2 text-sm text-gray-700">
                    @foreach ($doctor->schedules as $schedule)
                        <li class="flex justify-between items-center p-3 bg-teal-50 rounded-lg border border-teal-200">
                            <span class="font-medium text-teal-800 w-1/3">
                                <i class="far fa-clock mr-2"></i> {{ $schedule->day }}
                            </span>
                            <span class="text-gray-800 w-2/3 text-right">
                                **{{ $schedule->start_time }}** s/d **{{ $schedule->end_time }}** </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-red-600 p-3 bg-red-100 rounded-lg border border-red-300">
                    Saat ini, tidak ada jadwal praktik yang terdaftar untuk dokter ini.
                </p>
            @endif
        </div>
    @endforeach

</div>
@endsection