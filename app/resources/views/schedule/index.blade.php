@extends('layouts.app-public')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Jadwal Perpustakaan Keliling (Pusling)</h1>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
        <p class="text-blue-700">📅 <strong>Menampilkan jadwal yang akan datang dan sedang berlangsung</strong></p>
        <p class="text-sm text-blue-600 mt-1">Jadwal yang sudah selesai akan otomatis disembunyikan</p>
    </div>

    <form action="{{ route('schedule.index') }}" method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="place" placeholder="Cari lokasi..." value="{{ $place }}" class="border rounded p-2" />
        <input type="text" name="booker" placeholder="Cari pemesan..." value="{{ $booker }}" class="border rounded p-2" />
        <input type="text" name="category" placeholder="Cari kategori..." value="{{ $category }}" class="border rounded p-2" />
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Cari</button>
    </form>

    @if($schedules->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($schedules as $schedule)
        @php
            $r = $schedule->reservation;
            if (!$r) continue;
            
            $status = strtolower(trim($r->status ?? 'confirmed'));
            $badge = 'bg-green-600'; // Only confirmed schedules are shown
            $startTime = \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i');
            $endTime = \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i');
            $duration = $r->duration_minutes ?? 120;
            $duration = $r->duration_minutes ?? 60;
        @endphp
        <div class="bg-white shadow rounded p-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold">📅 {{ \Illuminate\Support\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}</h2>
                <span class="text-white text-xs px-2 py-1 rounded {{ $badge }}">Terjadwal</span>
            </div>
            <p><strong>⏰ Waktu:</strong> {{ $startTime }} - {{ $endTime }} ({{ $duration }} menit)</p>
            <p><strong>👤 Nama:</strong> {{ $r->full_name }}</p>
            <p><strong>📂 Kategori:</strong> {{ $r->category ?? '-' }}</p>
            <p><strong>💼 Kegiatan:</strong> {{ $r->occupation }}</p>
            <p><strong>📍 Alamat:</strong> {{ $r->address }}</p>
            <p><strong>📞 Nomor HP:</strong> {{ $r->phone_number }}</p>
            <p><strong>👥 Jenis Kelamin:</strong> {{ $r->gender }}</p>
            <a class="text-blue-600 underline inline-block mt-2" target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($r->address) }}">📍 Lihat di Google Maps</a>
        </div>
        @endforeach
    </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <p class="text-yellow-700">⚠️ <strong>Belum ada jadwal yang tersedia.</strong></p>
            <p class="text-sm text-yellow-600 mt-1">Jadwal akan ditampilkan setelah admin menggenerate jadwal dari reservasi yang sudah dikonfirmasi.</p>
        </div>
    @endif

    <div class="mt-4">{{ $schedules->links() }}</div>
</div>
@endsection
