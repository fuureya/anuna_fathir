@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-6 space-y-6">

    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold">Semua Jadwal Terdaftar</h2>
        <span class="text-sm text-gray-500">
            {{ $schedules->total() }} reservasi ditemukan
        </span>
    </div>

    @foreach ($schedules as $schedule)
    <div class="bg-white rounded-xl shadow-sm border-l-4 border-purple-500 p-6">

        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div class="text-purple-600 font-semibold flex items-center gap-2">
                ⏰
                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WITA
            </div>

            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">
                ✓ Terkonfirmasi
            </span>
        </div>

        {{-- Info utama --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 text-sm text-gray-700">
            <div class="flex items-start gap-2">
                📅
                <div>
                    <div class="text-xs text-gray-500">Tanggal Kunjungan</div>
                    {{ \Carbon\Carbon::parse($schedule->scheduled_date)->translatedFormat('d F Y') }}
                </div>
            </div>

            <div class="flex items-start gap-2">
                🏢
                <div>
                    <div class="text-xs text-gray-500">Instansi/Kegiatan</div>
                    {{ $schedule->reservation->occupation ?? '-' }}
                </div>
            </div>

            <div class="flex items-start gap-2">
                📚
                <div>
                    <div class="text-xs text-gray-500">Kategori</div>
                    {{ $schedule->reservation->category ?? '-' }}
                </div>
            </div>

            <div class="flex items-start gap-2">
                📍
                <div>
                    <div class="text-xs text-gray-500">Lokasi</div>
                    {{ $schedule->reservation->address ?? '-' }}
                </div>
            </div>
        </div>

        {{-- FCFS --}}
        <div class="mt-5 bg-purple-50 rounded-lg p-4">
            <div class="text-sm font-medium text-purple-700 mb-3">
                📌 Informasi Antrian FCFS
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-sm">
                <div class="bg-white rounded-lg p-3">
                    <div class="text-xs text-gray-500">Posisi</div>
                    <div class="font-bold text-purple-700">
                        {{ $schedule->queue_position ?? '-' }}
                    </div>
                </div>

                <div class="bg-white rounded-lg p-3">
                    <div class="text-xs text-gray-500">Waktu Tunggu</div>
                    <div class="font-bold text-purple-700">
                        {{ $schedule->waiting_time ?? 0 }}m
                    </div>
                </div>

                <div class="bg-white rounded-lg p-3">
                    <div class="text-xs text-gray-500">TAT</div>
                    <div class="font-bold text-purple-700">
                        {{ $schedule->turnaround_time ?? 0 }}m
                    </div>
                </div>

                <div class="bg-white rounded-lg p-3">
                    <div class="text-xs text-gray-500">Mulai Layanan</div>
                    <div class="font-bold text-purple-700">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endforeach

    {{ $schedules->links() }}
</div>
@endsection
