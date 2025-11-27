@extends('layouts.app')

@section('title', 'Daftar Janji Temu')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">

    <h2 class="text-2xl font-bold mb-4">Daftar Janji Temu Saya</h2>

    @if($appointments->isEmpty())
        <p class="text-gray-600">Belum ada janji temu yang dibuat.</p>
    @else
        <table class="w-full border mt-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Dokter</th>
                    <th class="p-2 border">Poli</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $a)
                <tr>
                    <td class="p-2 border">{{ $a->schedule->date }}</td>
                    <td class="p-2 border">{{ $a->doctor->name }}</td>
                    <td class="p-2 border">{{ $a->doctor->doctorDetail->poli->name }}</td>
                    <td class="p-2 border">
                        <span class="px-3 py-1 rounded text-white
                            {{ $a->status == 'Pending' ? 'bg-yellow-500' : '' }}
                            {{ $a->status == 'Approved' ? 'bg-green-600' : '' }}
                            {{ $a->status == 'Rejected' ? 'bg-red-600' : '' }}
                            {{ $a->status == 'Selesai' ? 'bg-blue-600' : '' }}">
                            {{ $a->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
