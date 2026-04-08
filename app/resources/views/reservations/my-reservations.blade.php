@extends('layouts.app-public')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .page-header {
        background: linear-gradient(135deg, #0693E3 0%, #0056b3 100%);
        color: white;
        padding: 40px 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .page-header h1 {
        margin: 0 0 10px 0;
        font-size: 32px;
        font-weight: 700;
    }
    
    .page-header p {
        margin: 0;
        font-size: 16px;
        opacity: 0.95;
    }
    
    .reservation-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 5px solid #ddd;
        transition: all 0.3s ease;
    }
    
    .reservation-card:hover {
        box-shadow: 0 6px 25px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .reservation-card.pending {
        border-left-color: #f59e0b;
    }
    
    .reservation-card.confirmed {
        border-left-color: #10b981;
    }
    
    .reservation-card.completed {
        border-left-color: #3b82f6;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    }
    
    .reservation-card.rejected {
        border-left-color: #ef4444;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-badge.pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .status-badge.confirmed {
        background: #d1fae5;
        color: #059669;
    }
    
    .status-badge.completed {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .status-badge.rejected {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .rejection-reason-box {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: 8px;
        padding: 15px;
        margin: 15px 0;
    }
    
    .reason-header {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #92400e;
        margin-bottom: 10px;
    }
    
    .reason-header svg {
        flex-shrink: 0;
    }
    
    .reason-header strong {
        font-size: 14px;
        font-weight: 600;
    }
    
    .reason-text {
        color: #78350f;
        font-size: 14px;
        line-height: 1.6;
        margin: 0;
        padding-left: 28px;
    }
    
    .card-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .detail-item {
        display: flex;
        align-items: start;
        gap: 10px;
    }
    
    .detail-item svg {
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
    
    .card-actions {
        display: flex;
        gap: 10px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: #0693E3;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .empty-state svg {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        color: #d1d5db;
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
    
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
    
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 24px;
        }
        
        .card-header {
            flex-direction: column;
            align-items: start;
        }
        
        .card-details {
            grid-template-columns: 1fr;
        }
        
        .card-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>📋 Reservasi Saya</h1>
        <p>Pantau status reservasi perpustakaan keliling Anda di sini</p>
    </div>
    
    @if($reservations->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3>Belum Ada Reservasi</h3>
            <p>Anda belum memiliki reservasi. Buat reservasi pertama Anda sekarang!</p>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Reservasi Baru
            </a>
        </div>
    @else
        @foreach($reservations as $reservation)
            @php
                // Cek apakah reservasi sudah selesai (confirmed + waktu sudah lewat)
                $isCompleted = false;
                if ($reservation->status === 'confirmed' && $reservation->reservation_date && $reservation->end_time) {
                    $endDateTime = \Carbon\Carbon::parse($reservation->reservation_date->format('Y-m-d') . ' ' . $reservation->end_time);
                    $isCompleted = $endDateTime->isPast();
                } elseif ($reservation->status === 'confirmed' && $reservation->reservation_date) {
                    // Fallback: jika tidak ada end_time, cek apakah tanggal sudah lewat
                    $isCompleted = $reservation->reservation_date->endOfDay()->isPast();
                }
                $cardClass = $isCompleted ? 'completed' : $reservation->status;
            @endphp
            <div class="reservation-card {{ $cardClass }}">
                <div class="card-header">
                    <h3 class="card-title">{{ $reservation->occupation }}</h3>
                    <span class="status-badge {{ $cardClass }}">
                        @if($reservation->status === 'pending')
                            ⏳ Menunggu Persetujuan
                        @elseif($reservation->status === 'confirmed' && $isCompleted)
                            ✅ Selesai
                        @elseif($reservation->status === 'confirmed')
                            ✅ Disetujui
                        @else
                            ❌ Ditolak
                        @endif
                    </span>
                </div>
                
                <div class="card-details">
                    <div class="detail-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div class="detail-content">
                            <div class="detail-label">Nama Pemohon</div>
                            <div class="detail-value">{{ $reservation->full_name }}</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="detail-content">
                            <div class="detail-label">Tanggal Kunjungan</div>
                            <div class="detail-value">{{ optional($reservation->reservation_date)->format('d F Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="detail-content">
                            <div class="detail-label">Waktu Kunjungan</div>
                            <div class="detail-value">
                                @if($reservation->visit_time)
                                    {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }}
                                    @if($reservation->end_time)
                                        - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    @endif
                                    WITA
                                    @if($reservation->end_time)
                                        <br><small style="color: #666; font-size: 12px;">(Durasi: 2 jam)</small>
                                    @endif
                                @else
                                    <span style="color: #999;">Belum ditentukan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <div class="detail-content">
                            <div class="detail-label">Kategori</div>
                            <div class="detail-value">{{ ucfirst($reservation->category) }}</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="detail-content">
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">{{ $reservation->address }}</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div class="detail-content">
                            <div class="detail-label">Nomor Telepon</div>
                            <div class="detail-value">{{ $reservation->phone_number }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- FCFS Metrics Section -->
                @if($reservation->status === 'confirmed' && $reservation->start_time)
                    <div style="background: linear-gradient(135deg, #f3e7ff 0%, #e9d5ff 100%); border-radius: 10px; padding: 20px; margin: 20px 0; border: 2px solid #d8b4fe;">
                        <h4 style="color: #7c3aed; margin: 0 0 15px 0; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                            📊 Informasi Antrian FCFS
                        </h4>
                        <div class="card-details" style="margin-bottom: 0;">
                            <div class="detail-item">
                                <div style="background: #9333ea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold; font-size: 18px;">
                                    #{{ $reservation->queue_position ?? '-' }}
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Posisi Antrian</div>
                                    <div class="detail-value" style="color: #7c3aed;">
                                        @if($reservation->queue_position == 1)
                                            🏆 Prioritas Pertama
                                        @elseif($reservation->queue_position == 2)
                                            🥈 Prioritas Kedua
                                        @elseif($reservation->queue_position == 3)
                                            🥉 Prioritas Ketiga
                                        @else
                                            Urutan ke-{{ $reservation->queue_position }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div style="background: #3b82f6; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                    ⏱️
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Waktu Tunggu</div>
                                    <div class="detail-value" style="color: #3b82f6;">
                                        {{ $reservation->waiting_time ?? 0 }} menit
                                        @if($reservation->waiting_time == 0)
                                            <small style="color: #10b981; font-weight: 600;">(Langsung dilayani!)</small>
                                        @elseif($reservation->waiting_time < 60)
                                            <small style="color: #10b981;">({{ $reservation->waiting_time }} menit)</small>
                                        @else
                                            <small style="color: #f59e0b;">({{ floor($reservation->waiting_time / 60) }} jam {{ $reservation->waiting_time % 60 }} menit)</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                    📈
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Total Waktu Proses</div>
                                    <div class="detail-value" style="color: #10b981;">
                                        {{ $reservation->turnaround_time ?? 0 }} menit
                                        @if($reservation->turnaround_time >= 60)
                                            <small>({{ floor($reservation->turnaround_time / 60) }} jam {{ $reservation->turnaround_time % 60 }} menit)</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div style="background: #06b6d4; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                    🚀
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Waktu Mulai Layanan</div>
                                    <div class="detail-value" style="color: #06b6d4;">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} WITA
                                        <br><small style="color: #666;">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d F Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 15px; padding: 12px; background: white; border-radius: 8px; border-left: 4px solid #7c3aed;">
                            <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.6;">
                                <strong style="color: #7c3aed;">💡 Info:</strong> Jadwal telah dioptimalkan menggunakan algoritma FCFS (First Come First Served). 
                                Waktu mulai layanan ditentukan berdasarkan urutan pendaftaran untuk memastikan pelayanan yang adil dan efisien.
                            </p>
                        </div>
                    </div>
                @endif
                
                @if($reservation->status === 'rejected' && $reservation->rejection_reason)
                    <div class="rejection-reason-box">
                        <div class="reason-header">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <strong>Alasan Penolakan:</strong>
                        </div>
                        <p class="reason-text">{{ $reservation->rejection_reason }}</p>
                    </div>
                @endif
                
                <div class="card-actions">
                    @if($reservation->request_letter)
                        <a href="{{ Storage::url($reservation->request_letter) }}" 
                           target="_blank" 
                           class="btn btn-secondary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Lihat Surat Permohonan
                        </a>
                    @endif
                    
                    @if($reservation->status === 'confirmed')
                        <a href="{{ route('qr.verify', $reservation->id) }}" 
                           class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            Lihat Detail & QR Code
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
        
        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="pagination">
                {{ $reservations->links() }}
            </div>
        @endif
    @endif
    
    <!-- Add new reservation button -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('reservations.create') }}" class="btn btn-primary" style="font-size: 16px; padding: 14px 30px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Reservasi Baru
        </a>
    </div>
</div>
@endsection
