@extends('layouts.app-public')

@section('title', 'Jadwal Reservasi Perpustakaan Keliling')

@push('styles')
<style>
    .schedule-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .page-header {
        background: #0693E3;
        color: white;
        padding: 50px 30px;
        border-radius: 20px;
        margin-bottom: 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(6, 147, 227, 0.3);
    }
    
    .page-header h1 {
        margin: 0 0 15px 0;
        font-size: 36px;
        font-weight: 700;
    }
    
    .page-header p {
        margin: 0;
        font-size: 18px;
        opacity: 0.95;
    }
    
    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-group label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }
    
    .filter-group input,
    .filter-group select {
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #0693E3;
        box-shadow: 0 0 0 3px rgba(6, 147, 227, 0.1);
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #0693E3 0%, #1e3a5f 100%);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 147, 227, 0.4);
    }
    
    .btn-reset {
        background: white;
        color: #0693E3;
        border: 2px solid #0693E3;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-reset:hover {
        background: #f3f4f6;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .stat-icon {
        font-size: 36px;
        margin-bottom: 10px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #0693E3;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }
    
    .calendar-view {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .date-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .date-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    
    .schedule-grid {
        display: grid;
        gap: 20px;
    }
    
    .schedule-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 25px;
        border-left: 5px solid #0693E3;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .schedule-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 20px rgba(6, 147, 227, 0.15);
    }
    
    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .schedule-time {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 20px;
        font-weight: 700;
        color: #0693E3;
    }
    
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-badge.confirmed {
        background: #d1fae5;
        color: #059669;
    }
    
    .schedule-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .detail-item {
        display: flex;
        align-items: start;
        gap: 10px;
    }
    
    .detail-icon {
        width: 20px;
        height: 20px;
        color: #0693E3;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .detail-content {
        flex: 1;
    }
    
    .detail-label {
        font-size: 12px;
        color: #999;
        margin-bottom: 3px;
    }
    
    .detail-value {
        font-size: 15px;
        color: #2c3e50;
        font-weight: 500;
    }
    
    .fcfs-metrics {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
        border: 2px solid #7dd3fc;
    }
    
    .fcfs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .fcfs-item {
        text-align: center;
        padding: 10px;
        background: white;
        border-radius: 8px;
    }
    
    .fcfs-label {
        font-size: 11px;
        color: #666;
        margin-bottom: 5px;
    }
    
    .fcfs-value {
        font-size: 18px;
        font-weight: 700;
        color: #0693E3;
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .empty-state-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #999;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 28px;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .schedule-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="schedule-container">
    <div class="page-header">
        <h1>📅 Jadwal Reservasi Perpustakaan Keliling</h1>
        <p>Lihat semua jadwal reservasi yang sudah terdaftar</p>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <h2 style="margin: 0 0 20px 0; font-size: 20px; color: #333;">🔍 Filter Jadwal</h2>
        <form method="GET" action="{{ route('reservations.schedule') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="date_from">Dari Tanggal</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                
                <div class="filter-group">
                    <label for="date_to">Sampai Tanggal</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                
                <div class="filter-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category">
                        <option value="">Semua Kategori</option>
                        <option value="Event" {{ request('category') === 'Event' ? 'selected' : '' }}>Event</option>
                        <option value="Sekolah" {{ request('category') === 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                        <option value="Organisasi" {{ request('category') === 'Organisasi' ? 'selected' : '' }}>Organisasi</option>
                        <option value="Instansi" {{ request('category') === 'Instansi' ? 'selected' : '' }}>Instansi</option>
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn-filter">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('reservations.schedule') }}" class="btn-reset">
                    Reset Filter
                </a>
            </div>
        </form>
    </div>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-value">{{ $totalReservations }}</div>
            <div class="stat-label">Total Reservasi</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $confirmedCount }}</div>
            <div class="stat-label">Terkonfirmasi</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-value">{{ $uniqueDates }}</div>
            <div class="stat-label">Tanggal Berbeda</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">⏱️</div>
            <div class="stat-value">{{ round($avgWaitingTime) }}</div>
            <div class="stat-label">Rata-rata WT (menit)</div>
        </div>
    </div>
    
    <!-- Calendar View -->
    <div class="calendar-view">
        <div class="date-header">
            <h2 class="date-title">
                @if(request('date_from') || request('date_to'))
                    Jadwal: {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d M Y') : 'Awal' }} - {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d M Y') : 'Sekarang' }}
                @else
                    Semua Jadwal Terdaftar
                @endif
            </h2>
            <div style="color: #666; font-size: 14px;">
                {{ $reservations->total() }} reservasi ditemukan
            </div>
        </div>
        
        @if($reservations->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Belum Ada Jadwal</h3>
                <p>Belum ada reservasi yang terdaftar untuk filter ini.</p>
                <a href="{{ route('reservations.create') }}" class="btn-filter">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Reservasi Baru
                </a>
            </div>
        @else
            <div class="schedule-grid">
                @foreach($reservations as $reservation)
                    <div class="schedule-card">
                        <div class="schedule-header">
                            <div class="schedule-time">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @if($reservation->visit_time)
                                    {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }}
                                    @if($reservation->end_time)
                                        - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    @endif
                                    WITA
                                @else
                                    Waktu belum ditentukan
                                @endif
                            </div>
                            <span class="status-badge confirmed">
                                ✓ Terkonfirmasi
                            </span>
                        </div>
                        
                        <div class="schedule-details">
                            <div class="detail-item">
                                <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="detail-content">
                                    <div class="detail-label">Tanggal Kunjungan</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d F Y') }}</div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div class="detail-content">
                                    <div class="detail-label">Instansi/Kegiatan</div>
                                    <div class="detail-value">{{ $reservation->occupation }}</div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <div class="detail-content">
                                    <div class="detail-label">Kategori</div>
                                    <div class="detail-value">{{ ucfirst($reservation->category) }}</div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div class="detail-content">
                                    <div class="detail-label">Lokasi</div>
                                    <div class="detail-value">{{ $reservation->address }}</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Always show FCFS info for confirmed reservations --}}
                        @if($reservation->status === 'confirmed')
                            @php
                                // Calculate queue position if not set
                                $queuePosition = $reservation->queue_position;
                                if (!$queuePosition) {
                                    $queuePosition = \App\Models\Reservation::where('reservation_date', $reservation->reservation_date)
                                        ->where('status', 'confirmed')
                                        ->where(function($q) use ($reservation) {
                                            $q->where('visit_time', '<', $reservation->visit_time)
                                              ->orWhere(function($q2) use ($reservation) {
                                                  $q2->where('visit_time', $reservation->visit_time)
                                                     ->where('id', '<=', $reservation->id);
                                              });
                                        })
                                        ->count();
                                }
                                
                                // Calculate waiting time if not set
                                $waitingTime = $reservation->waiting_time ?? 0;
                                if (!$waitingTime && $reservation->visit_time) {
                                    // Calculate based on queue position and standard duration
                                    $standardDuration = $reservation->duration_minutes ?? 120;
                                    $waitingTime = ($queuePosition - 1) * $standardDuration;
                                }
                                
                                // Calculate TAT (Turnaround Time)
                                $tat = $reservation->turnaround_time ?? ($waitingTime + ($reservation->duration_minutes ?? 120));
                                
                                // Get start time
                                $startTime = $reservation->start_time 
                                    ? \Carbon\Carbon::parse($reservation->start_time)->format('H:i')
                                    : ($reservation->visit_time 
                                        ? \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') 
                                        : '-');
                                
                                // Format waktu ke jam/menit yang mudah dibaca
                                $formatTime = function($minutes) {
                                    if ($minutes == 0) return '0m';
                                    if ($minutes < 60) return $minutes . 'm';
                                    
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                    
                                    if ($hours >= 24) {
                                        $days = floor($hours / 24);
                                        $hours = $hours % 24;
                                        if ($hours > 0) {
                                            return $days . 'h ' . $hours . 'j ' . ($mins > 0 ? $mins . 'm' : '');
                                        }
                                        return $days . 'h ' . ($mins > 0 ? $mins . 'm' : '');
                                    }
                                    
                                    return $hours . 'j ' . ($mins > 0 ? $mins . 'm' : '');
                                };
                                
                                $formattedWT = $formatTime($waitingTime);
                                $formattedTAT = $formatTime($tat);
                                
                                // Hitung sisa waktu sampai tanggal reservasi
                                $now = \Carbon\Carbon::now();
                                $reservationDateTime = \Carbon\Carbon::parse($reservation->reservation_date);
                                if ($reservation->visit_time) {
                                    $reservationDateTime = \Carbon\Carbon::parse($reservation->reservation_date->format('Y-m-d') . ' ' . $reservation->visit_time);
                                }
                                
                                $daysUntil = '';
                                if ($reservationDateTime->isFuture()) {
                                    $diffInDays = (int) $now->diffInDays($reservationDateTime, false);
                                    $diffInHours = (int) ($now->diffInHours($reservationDateTime, false) % 24);
                                    
                                    if ($diffInDays > 0) {
                                        $daysUntil = $diffInDays . ' hari ' . ($diffInHours > 0 ? $diffInHours . ' jam lagi' : 'lagi');
                                    } else {
                                        $diffInHours = (int) $now->diffInHours($reservationDateTime, false);
                                        $diffInMins = (int) ($now->diffInMinutes($reservationDateTime, false) % 60);
                                        if ($diffInHours > 0) {
                                            $daysUntil = $diffInHours . ' jam ' . ($diffInMins > 0 ? $diffInMins . ' menit lagi' : 'lagi');
                                        } else {
                                            $daysUntil = $diffInMins . ' menit lagi';
                                        }
                                    }
                                } elseif ($reservationDateTime->isToday()) {
                                    $daysUntil = 'Hari ini';
                                } else {
                                    $daysUntil = 'Sudah lewat';
                                }
                            @endphp
                            <div class="fcfs-metrics">
                                <div style="font-size: 13px; font-weight: 600; color: #0693E3; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Informasi Antrian FCFS
                                </div>
                                <div class="fcfs-grid">
                                    <div class="fcfs-item">
                                        <div class="fcfs-label">Posisi #</div>
                                        <div class="fcfs-value">{{ $queuePosition ?: '-' }}</div>
                                    </div>
                                    <div class="fcfs-item">
                                        <div class="fcfs-label">Countdown</div>
                                        <div class="fcfs-value" style="font-size: 12px; color: {{ $reservationDateTime->isFuture() ? '#10b981' : '#6b7280' }};">{{ $daysUntil }}</div>
                                    </div>
                                    <div class="fcfs-item">
                                        <div class="fcfs-label">Waktu Tunggu</div>
                                        <div class="fcfs-value">{{ $formattedWT }}</div>
                                    </div>
                                    <div class="fcfs-item">
                                        <div class="fcfs-label">TAT</div>
                                        <div class="fcfs-value">{{ $formattedTAT }}</div>
                                    </div>
                                    <div class="fcfs-item">
                                        <div class="fcfs-label">Mulai Layanan</div>
                                        <div class="fcfs-value" style="font-size: 14px;">{{ $startTime }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($reservations->hasPages())
                <div style="margin-top: 30px;">
                    {{ $reservations->links() }}
                </div>
            @endif
        @endif
    </div>
    
    <!-- CTA Button -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('reservations.create') }}" class="btn-filter" style="font-size: 16px; padding: 14px 30px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Reservasi Baru
        </a>
    </div>
</div>
@endsection
