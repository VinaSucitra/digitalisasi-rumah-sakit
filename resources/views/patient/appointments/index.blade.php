@extends('layouts.patient')

@section('title', 'Daftar Janji Temu')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">

    <h2 class="text-2xl font-bold mb-4">Daftar Janji Temu Saya</h2>

    @if($appointments->isEmpty())
        <p class="text-gray-600">Belum ada janji temu yang dibuat.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border mt-4 min-w-[600px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Tanggal & Waktu</th>
                        <th class="p-2 border">Dokter</th>
                        <th class="p-2 border">Poli</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $a)
                    <tr>
                        {{-- Tanggal dan Waktu dari Schedule --}}
                        <td class="p-2 border whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($a->booking_date ?? now())->format('d M Y') }}
                            <br>
                            {{-- Mengambil Waktu dari Schedule --}}
                            ({{ substr($a->schedule->start_time ?? 'N/A', 0, 5) }} - {{ substr($a->schedule->end_time ?? 'N/A', 0, 5) }})
                        </td>
                        
                        {{-- Dokter (Menggunakan Safe Access untuk Doctor User) --}}
                        <td class="p-2 border whitespace-nowrap">
                            {{ $a->doctor->user->name ?? 'Dokter Tidak Dikenal' }}
                        </td>
                        
                        {{-- 🔥 PERBAIKAN KRITIS: Menggunakan Safe Access Operator (?->) --}}
                        <td class="p-2 border whitespace-nowrap">
                            {{ $a->doctor->poli->name ?? 'N/A' }} 
                        </td>
                        
                        {{-- Status --}}
                        <td class="p-2 border">
                            @php
                                $status = strtolower($a->status ?? 'pending');
                                $color = match($status) {
                                    'approved' => 'bg-green-600',
                                    'rejected' => 'bg-red-600',
                                    'done'     => 'bg-blue-600',
                                    default    => 'bg-yellow-500', // pending
                                };
                            @endphp
                            <span class="px-3 py-1 text-sm rounded text-white font-semibold whitespace-nowrap {{ $color }}">
                                {{ ucfirst($a->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection