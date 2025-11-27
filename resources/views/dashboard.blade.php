@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('header')
    Dashboard Admin
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900 border-l-4 border-green-500 bg-green-50 rounded-lg">
                    <p class="font-bold text-lg text-green-700">
                        <i class="fas fa-check-circle mr-2"></i> {{ __("Anda berhasil login!") }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Anda saat ini login sebagai user dengan peran: <span class="font-mono text-indigo-600">{{ Auth::user()->role ?? 'N/A' }}</span>.
                        Silakan gunakan menu di *sidebar* sebelah kiri untuk mulai mengelola data.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection