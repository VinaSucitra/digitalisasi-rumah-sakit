@extends('layouts.patient')

@section('title', 'Riwayat Pemeriksaan')
@section('page_title', 'Riwayat Pemeriksaan')

@section('content')
    <div class="bg-white shadow-xl rounded-xl p-6 md:p-8 max-w-3xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Riwayat Pemeriksaan</h2>
        <p class="text-gray-600 mb-6">Berikut adalah riwayat rekam medis Anda yang telah tercatat.</p>

        {{-- Jika tidak ada rekam medis --}}
        @if($medicalRecords->isEmpty())
            <div class="p-4 border border-gray-300 rounded-lg">
                <p class="text-gray-500">Belum ada rekam medis yang tercatat.</p>
            </div>
        @else
            {{-- Tabel daftar rekam medis --}}
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-teal-100">
                        <th class="px-4 py-2 text-left">Tanggal Pemeriksaan</th>
                        <th class="px-4 py-2 text-left">Dokter</th>
                        <th class="px-4 py-2 text-left">Poli</th>
                        <th class="px-4 py-2 text-left">Resep</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicalRecords as $record)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $record->visit_date }}</td>
                            <td class="px-4 py-2">{{ $record->doctor->user->name }}</td>
                            <td class="px-4 py-2">{{ $record->doctor->poli->name }}</td>
                            <td class="px-4 py-2">
                                @if($record->prescriptions->count() > 0)
                                    <span class="text-green-500">Tersedia</span>
                                @else
                                    <span class="text-red-500">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('patient.medical_records.show', $record->id) }}" class="text-teal-500 hover:text-teal-700">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $medicalRecords->links() }}
            </div>
        @endif
    </div>
@endsection
