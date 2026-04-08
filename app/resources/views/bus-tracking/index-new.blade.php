<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Bus Perpustakaan Keliling</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/bus-tracking.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="tracking-header">
        <h1>🚌 Tracking Bus Perpustakaan Keliling</h1>
        <p>Pantau lokasi bus perpustakaan keliling secara real-time</p>
    </header>
    
    <!-- Container -->
    <div class="tracking-container">
        <!-- Date Selector -->
        <div class="date-selector">
            <label for="tracking-date">Tanggal:</label>
            <input type="date" id="tracking-date" value="{{ $date }}">
            <button onclick="changeDate()">Lihat</button>
        </div>
        
        <!-- Main Layout -->
        <div class="tracking-layout">
            <!-- Map -->
            <section class="map-section">
                <div id="map"></div>
            </section>
            
            <!-- Sidebar -->
            <aside class="sidebar-section">
                <!-- Bus Status Card -->
                <div class="info-card">
                    <h3>📍 Status Bus</h3>
                    
                    <div class="status-display">
                        <div class="status-icon {{ $tracking->bus_status ?? 'idle' }}">
                            @if($tracking)
                                {{ $tracking->bus_status == 'on_the_way' ? '🚗' : ($tracking->bus_status == 'arrived' ? '🏁' : ($tracking->bus_status == 'serving' ? '📚' : ($tracking->bus_status == 'finished' ? '✅' : '🅿️'))) }}
                            @else
                                🅿️
                            @endif
                        </div>
                        <div class="status-details">
                            <h4>Status Saat Ini</h4>
                            <p id="current-status">
                                @if($tracking)
                                    {{ $tracking->bus_status == 'idle' ? 'Standby' : ($tracking->bus_status == 'on_the_way' ? 'Dalam Perjalanan' : ($tracking->bus_status == 'arrived' ? 'Tiba di Lokasi' : ($tracking->bus_status == 'serving' ? 'Melayani' : 'Selesai'))) }}
                                @else
                                    Belum Ada Jadwal
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($tracking && $tracking->currentReservation)
                    <div class="status-display">
                        <div class="status-icon" style="background: #dbeafe;">📍</div>
                        <div class="status-details">
                            <h4>Lokasi Tujuan</h4>
                            <p id="current-location">{{ $tracking->currentReservation->address }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="auto-refresh">
                        🔄 Auto-refresh setiap 10 detik
                    </div>
                </div>
                
                <!-- Reservations List -->
                <div class="info-card">
                    <h3>📅 Jadwal Hari Ini</h3>
                    
                    @if($reservations->count() > 0)
                        <div class="reservations-container">
                            @foreach($reservations as $index => $reservation)
                            <div class="reservation-item {{ $tracking && $tracking->current_reservation_id == $reservation->id ? 'active' : '' }}" id="reservation-{{ $reservation->id }}">
                                <div class="reservation-number">{{ $index + 1 }}</div>
                                <h4>{{ $reservation->occupation }}</h4>
                                <p>📍 {{ $reservation->address }}</p>
                                <p>⏰ {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WITA</p>
                                <p>👤 {{ $reservation->full_name }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">📅</div>
                            <p>Tidak ada jadwal untuk tanggal ini</p>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/bus-tracking.js') }}"></script>
    <script>
        // Initialize with server data
        const initialReservations = @json($reservations);
        const initialTracking = @json($tracking);
        const initialDate = '{{ $date }}';
        
        // Start tracking
        initBusTracking(initialReservations, initialTracking, initialDate);
    </script>
</body>
</html>
