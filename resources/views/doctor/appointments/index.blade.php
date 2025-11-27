@extends('layouts.doctor')

@section('header', 'Janji Temu Saya')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Nama Pasien</th>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $a)
            <tr>
                <td class="p-2">{{ $a->patient->name }}</td>
                <td class="p-2">{{ $a->date }}</td>
                <td class="p-2">{{ $a->status }}</td>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
