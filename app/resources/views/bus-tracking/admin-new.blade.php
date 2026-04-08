<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kontrol Bus - Admin</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/bus-tracking-admin.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <h1>🚌 Kontrol Bus Perpustakaan</h1>
        <p>Admin Panel - Update Status Perjalanan</p>
    </header>
    
    <!-- Container -->
    <div class="admin-container">
        <!-- Date Card -->
        <div class="admin-card">
            <div class="form-group">
                <label class="form-label">Tanggal Tracking</label>
                <input type="date" id="tracking-date" value="{{ $date }}" class="form-input" onchange="changeDate()">
            </div>
        </div>
        
        <!-- Alert Container -->
        <div id="alert-container"></div>
        
        <!-- Current Status Card -->
        <div class="admin-card">
            <h3>📊 Status Saat Ini</h3>
            
            <span class="status-badge {{ $tracking->bus_status ?? 'idle' }}" id="current-status-badge">
                @if($tracking)
                    {{ $tracking->bus_status == 'idle' ? '🅿️ Standby' : ($tracking->bus_status == 'on_the_way' ? '🚗 Dalam Perjalanan' : ($tracking->bus_status == 'arrived' ? '🏁 Tiba di Lokasi' : ($tracking->bus_status == 'serving' ? '📚 Melayani' : '✅ Selesai'))) }}
                @else
                    🅿️ Standby
                @endif
            </span>
            
            @if($tracking && $tracking->currentReservation)
            <div class="location-box">
                <strong>Lokasi Tujuan:</strong>
                {{ $tracking->currentReservation->occupation }}<br>
                {{ $tracking->currentReservation->address }}
            </div>
            @endif
            
            <div class="location-box" style="margin-top: 0.75rem;">
                <strong>Terakhir Update:</strong>
                <span id="last-update">
                    {{ $tracking ? $tracking->status_updated_at?->diffForHumans() : 'Belum ada update' }}
                </span>
            </div>
        </div>
        
        <!-- Map Toggle Card -->
        <div class="admin-card">
            <button class="btn btn-primary" onclick="toggleMap()">
                📍 <span id="map-toggle-text">Tampilkan Peta</span>
            </button>
        </div>
        
        <!-- Simulation Card -->
        <div class="admin-card">
            <div class="checkbox-group" onclick="toggleSimulation()">
                <input type="checkbox" id="simulate-movement">
                <label>🎮 Mode Simulasi Perjalanan (untuk testing)</label>
            </div>
            <div class="info-box" id="simulate-info">
                ℹ️ Mode simulasi akan menggerakkan bus secara otomatis menuju lokasi tujuan dengan kecepatan virtual. Gunakan untuk testing tanpa GPS.
            </div>
        </div>
        
        <!-- Map Container -->
        <div id="map-container" class="map-container">
            <div id="map"></div>
        </div>
        
        <!-- Reservations List -->
        <div class="admin-card">
            <h3>📅 Pilih Tujuan ({{ $reservations->count() }} Lokasi)</h3>
            
            @if($reservations->count() > 0)
                <div class="reservation-cards">
                    @foreach($reservations as $index => $reservation)
                    <div class="reservation-card {{ $tracking && $tracking->current_reservation_id == $reservation->id ? 'selected' : '' }}" 
                         onclick="selectReservation({{ $reservation->id }}, {{ $reservation->latitude ?? 0 }}, {{ $reservation->longitude ?? 0 }})"
                         id="card-{{ $reservation->id }}">
                        <div class="number">{{ $index + 1 }}</div>
                        <h4>{{ $reservation->occupation }}</h4>
                        <p>📍 {{ $reservation->address }}</p>
                        <p>⏰ {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WITA</p>
                        <p>👤 {{ $reservation->full_name }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>Tidak ada jadwal untuk tanggal ini</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Status Buttons (Fixed Bottom) -->
    <div class="status-buttons">
        <div class="button-grid">
            <button class="status-btn btn-idle" onclick="updateStatus('idle')" id="btn-idle">
                <span class="emoji">🅿️</span>
                <span>Standby</span>
            </button>
            
            <button class="status-btn btn-on-way" onclick="updateStatus('on_the_way')" id="btn-on-way">
                <span class="emoji">🚗</span>
                <span>Berangkat</span>
            </button>
            
            <button class="status-btn btn-arrived" onclick="updateStatus('arrived')" id="btn-arrived">
                <span class="emoji">🏁</span>
                <span>Tiba</span>
            </button>
            
            <button class="status-btn btn-serving" onclick="updateStatus('serving')" id="btn-serving">
                <span class="emoji">📚</span>
                <span>Melayani</span>
            </button>
            
            <button class="status-btn btn-finished" onclick="updateStatus('finished')" id="btn-finished">
                <span class="emoji">✅</span>
                <span>Selesai</span>
            </button>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/bus-tracking-admin.js') }}"></script>
    <script>
        // Initialize with server data
        const initialTracking = @json($tracking);
        const initialReservations = @json($reservations);
        
        // Start admin control
        initAdminControl(initialTracking, initialReservations);
    </script>
</body>
</html>
