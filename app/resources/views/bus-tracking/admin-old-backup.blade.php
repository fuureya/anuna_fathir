<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kontrol Bus - Admin</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding-bottom: 100px;
        }
        
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 3px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        
        .date-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .date-card input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .current-status {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .current-status h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .status-badge.idle { background: #e0e0e0; color: #666; }
        .status-badge.on_the_way { background: #fff3cd; color: #856404; }
        .status-badge.arrived { background: #d1ecf1; color: #0c5460; }
        .status-badge.serving { background: #d4edda; color: #155724; }
        .status-badge.finished { background: #f8d7da; color: #721c24; }
        
        .location-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .location-info strong {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        
        .reservations-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .reservations-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .reservation-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .reservation-card.selected {
            border-color: #f5576c;
            background: #fff5f7;
        }
        
        .reservation-card .number {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f5576c;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .reservation-card h4 {
            font-size: 15px;
            color: #333;
            margin-bottom: 8px;
            padding-right: 35px;
        }
        
        .reservation-card p {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .reservation-card .icon {
            display: inline-block;
            width: 16px;
        }
        
        .status-buttons {
            background: white;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 999;
        }
        
        .button-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .status-btn {
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        
        .status-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .status-btn .emoji {
            font-size: 24px;
        }
        
        .btn-idle {
            background: #e0e0e0;
            color: #666;
        }
        
        .btn-on-way {
            background: #fff3cd;
            color: #856404;
        }
        
        .btn-arrived {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .btn-serving {
            background: #d4edda;
            color: #155724;
        }
        
        .btn-finished {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-btn:active:not(:disabled) {
            transform: scale(0.95);
        }
        
        .map-toggle {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .map-toggle button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .simulate-toggle {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .simulate-toggle label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            cursor: pointer;
        }
        
        .simulate-toggle input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .simulate-info {
            margin-top: 10px;
            padding: 10px;
            background: #fff3cd;
            border-radius: 5px;
            font-size: 12px;
            color: #856404;
            display: none;
        }
        
        .simulate-info.active {
            display: block;
        }
        
        #map-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: none;
        }
        
        #map-container.show {
            display: block;
        }
        
        #map {
            width: 100%;
            height: 300px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚌 Kontrol Bus Perpustakaan</h1>
        <p>Admin Panel - Update Status Perjalanan</p>
    </div>
    
    <div class="container">
        <div class="date-card">
            <label style="font-size: 14px; color: #666; display: block; margin-bottom: 8px;">Tanggal Tracking</label>
            <input type="date" id="tracking-date" value="{{ $date }}" onchange="changeDate()">
        </div>
        
        <div id="alert-container"></div>
        
        <div class="current-status">
            <h3>📊 Status Saat Ini</h3>
            <span class="status-badge {{ $tracking->bus_status ?? 'idle' }}" id="current-status-badge">
                @if($tracking)
                    {{ $tracking->bus_status == 'idle' ? '🅿️ Standby' : ($tracking->bus_status == 'on_the_way' ? '🚗 Dalam Perjalanan' : ($tracking->bus_status == 'arrived' ? '🏁 Tiba di Lokasi' : ($tracking->bus_status == 'serving' ? '📚 Melayani' : '✅ Selesai'))) }}
                @else
                    🅿️ Standby
                @endif
            </span>
            
            @if($tracking && $tracking->currentReservation)
            <div class="location-info">
                <strong>Lokasi Tujuan:</strong>
                {{ $tracking->currentReservation->occupation }}<br>
                {{ $tracking->currentReservation->address }}
            </div>
            @endif
            
            <div class="location-info" style="margin-top: 10px;">
                <strong>Terakhir Update:</strong>
                <span id="last-update">
                    {{ $tracking ? $tracking->status_updated_at?->diffForHumans() : 'Belum ada update' }}
                </span>
            </div>
        </div>
        
        <div class="map-toggle">
            <button onclick="toggleMap()">
                📍 <span id="map-toggle-text">Tampilkan Peta</span>
            </button>
        </div>
        
        <div class="simulate-toggle">
            <label>
                <input type="checkbox" id="simulate-movement" onchange="toggleSimulation()">
                <span>🎮 Mode Simulasi Perjalanan (untuk testing)</span>
            </label>
            <div class="simulate-info" id="simulate-info">
                ℹ️ Mode simulasi akan menggerakkan bus secara otomatis menuju lokasi tujuan dengan kecepatan virtual. Gunakan untuk testing tanpa GPS.
            </div>
        </div>
        
        <div id="map-container">
            <div id="map"></div>
        </div>
        
        <div class="reservations-section">
            <h3>📅 Pilih Tujuan ({{ $reservations->count() }} Lokasi)</h3>
            
            @if($reservations->count() > 0)
                @foreach($reservations as $index => $reservation)
                <div class="reservation-card {{ $tracking && $tracking->current_reservation_id == $reservation->id ? 'selected' : '' }}" 
                     onclick="selectReservation({{ $reservation->id }}, {{ $reservation->latitude ?? 0 }}, {{ $reservation->longitude ?? 0 }})"
                     id="card-{{ $reservation->id }}">
                    <div class="number">{{ $index + 1 }}</div>
                    <h4>{{ $reservation->occupation }}</h4>
                    <p><span class="icon">📍</span> {{ $reservation->address }}</p>
                    <p><span class="icon">⏰</span> {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WITA</p>
                    <p><span class="icon">👤</span> {{ $reservation->full_name }}</p>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>Tidak ada jadwal untuk tanggal ini</p>
                </div>
            @endif
        </div>
    </div>
    
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
            
            <button class="status-btn btn-finished" onclick="updateStatus('finished')" id="btn-finished" style="grid-column: span 2;">
                <span class="emoji">✅</span>
                <span>Selesai</span>
            </button>
        </div>
    </div>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map = null;
        let selectedReservationId = {{ $tracking->current_reservation_id ?? 'null' }};
        let selectedLocation = { lat: null, lng: null };
        let currentMarker = null;
        let simulationMode = false;
        let simulationInterval = null;
        let currentSimulatedPosition = null;
        
        function initMap() {
            if (!map) {
                map = L.map('map').setView([-4.0095, 119.6230], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
            }
        }
        
        function toggleMap() {
            const container = document.getElementById('map-container');
            const text = document.getElementById('map-toggle-text');
            
            if (container.classList.contains('show')) {
                container.classList.remove('show');
                text.textContent = 'Tampilkan Peta';
            } else {
                container.classList.add('show');
                text.textContent = 'Sembunyikan Peta';
                initMap();
                setTimeout(() => map.invalidateSize(), 100);
                
                if (selectedLocation.lat && selectedLocation.lng) {
                    map.setView([selectedLocation.lat, selectedLocation.lng], 15);
                }
            }
        }
        
        function selectReservation(id, lat, lng) {
            selectedReservationId = id;
            selectedLocation = { lat, lng };
            
            // Update UI
            document.querySelectorAll('.reservation-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.getElementById('card-' + id).classList.add('selected');
            
            // Update map
            if (map && lat && lng) {
                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }
                
                currentMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background: #f5576c; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">📍</div>',
                        iconSize: [40, 40]
                    })
                }).addTo(map);
                
                map.setView([lat, lng], 15);
            }
        }
        
        async function updateStatus(status) {
            // Disable all buttons
            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.disabled = true;
            });
            
            showAlert('Mengupdate status...', 'loading');
            
            try {
                // Get current location
                let latitude = null;
                let longitude = null;
                
                if (simulationMode) {
                    // Use simulated position
                    if (currentSimulatedPosition) {
                        latitude = currentSimulatedPosition.lat;
                        longitude = currentSimulatedPosition.lng;
                    } else {
                        // Start from library position
                        latitude = -4.0095;
                        longitude = 119.6230;
                        currentSimulatedPosition = { lat: latitude, lng: longitude };
                    }
                    
                    // Start simulation when clicking "Berangkat"
                    if (status === 'on_the_way') {
                        startSimulation();
                    } else {
                        stopSimulation();
                    }
                } else {
                    // Try to get real GPS position
                    if (navigator.geolocation) {
                        try {
                            const position = await new Promise((resolve, reject) => {
                                navigator.geolocation.getCurrentPosition(
                                    resolve, 
                                    reject,
                                    { enableHighAccuracy: true, timeout: 10000 }
                                );
                            });
                            latitude = position.coords.latitude;
                            longitude = position.coords.longitude;
                        } catch (geoError) {
                            console.warn('GPS error:', geoError);
                            // Fallback to selected location or library
                            if (selectedLocation.lat && selectedLocation.lng) {
                                latitude = selectedLocation.lat;
                                longitude = selectedLocation.lng;
                            }
                        }
                    }
                }
                
                const date = document.getElementById('tracking-date').value;
                
                const response = await fetch('/admin/bus-tracking/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        date: date,
                        reservation_id: selectedReservationId,
                        status: status,
                        latitude: latitude,
                        longitude: longitude
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('✅ Status berhasil diupdate!', 'success');
                    
                    // Update UI
                    const statusMap = {
                        'idle': '🅿️ Standby',
                        'on_the_way': '🚗 Dalam Perjalanan',
                        'arrived': '🏁 Tiba di Lokasi',
                        'serving': '📚 Melayani',
                        'finished': '✅ Selesai'
                    };
                    
                    const badge = document.getElementById('current-status-badge');
                    badge.textContent = statusMap[status];
                    badge.className = 'status-badge ' + status;
                    
                    document.getElementById('last-update').textContent = 'Baru saja';
                    
                    // Update simulated position if in simulation mode
                    if (simulationMode && latitude && longitude) {
                        currentSimulatedPosition = { lat: latitude, lng: longitude };
                    }
                } else {
                    showAlert('❌ Gagal update status', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('❌ Terjadi kesalahan: ' + error.message, 'error');
            } finally {
                // Re-enable buttons
                document.querySelectorAll('.status-btn').forEach(btn => {
                    btn.disabled = false;
                });
            }
        }
        
        function showAlert(message, type) {
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-error' : 'alert-info');
            
            container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
            
            if (type === 'success' || type === 'error') {
                setTimeout(() => {
                    container.innerHTML = '';
                }, 3000);
            }
        }
        
        function changeDate() {
            const date = document.getElementById('tracking-date').value;
            window.location.href = `/admin/bus-tracking?date=${date}`;
        }
        
        // Toggle simulation mode
        function toggleSimulation() {
            const checkbox = document.getElementById('simulate-movement');
            const info = document.getElementById('simulate-info');
            simulationMode = checkbox.checked;
            
            if (simulationMode) {
                info.classList.add('active');
                showAlert('🎮 Mode simulasi aktif - Bus akan bergerak virtual menuju tujuan', 'info');
            } else {
                info.classList.remove('active');
                stopSimulation();
            }
        }
        
        // Start simulating movement towards destination
        function startSimulation() {
            if (!simulationMode || !selectedLocation.lat || !selectedLocation.lng) {
                return;
            }
            
            // Stop any existing simulation
            stopSimulation();
            
            // Set starting position (current position or near library)
            if (!currentSimulatedPosition) {
                currentSimulatedPosition = {
                    lat: -4.0095, // Parepare library position
                    lng: 119.6230
                };
            }
            
            const targetLat = selectedLocation.lat;
            const targetLng = selectedLocation.lng;
            const steps = 50; // Number of steps for smooth animation
            let currentStep = 0;
            
            const latStep = (targetLat - currentSimulatedPosition.lat) / steps;
            const lngStep = (targetLng - currentSimulatedPosition.lng) / steps;
            
            simulationInterval = setInterval(async () => {
                if (currentStep >= steps) {
                    stopSimulation();
                    showAlert('✅ Simulasi sampai di tujuan!', 'success');
                    return;
                }
                
                currentStep++;
                currentSimulatedPosition.lat += latStep;
                currentSimulatedPosition.lng += lngStep;
                
                // Update position to server
                try {
                    const date = document.getElementById('tracking-date').value;
                    await fetch('/admin/bus-tracking/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            date: date,
                            reservation_id: selectedReservationId,
                            status: 'on_the_way',
                            latitude: currentSimulatedPosition.lat,
                            longitude: currentSimulatedPosition.lng
                        })
                    });
                } catch (error) {
                    console.error('Simulation update error:', error);
                }
            }, 500); // Update every 500ms for smooth movement
        }
        
        // Stop simulation
        function stopSimulation() {
            if (simulationInterval) {
                clearInterval(simulationInterval);
                simulationInterval = null;
            }
        }
        
        // Initialize selected reservation on page load
        @if($tracking && $tracking->currentReservation)
            selectedLocation = {
                lat: {{ $tracking->currentReservation->latitude ?? 0 }},
                lng: {{ $tracking->currentReservation->longitude ?? 0 }}
            };
            currentSimulatedPosition = {
                lat: {{ $tracking->current_latitude ?? -4.0095 }},
                lng: {{ $tracking->current_longitude ?? 119.6230 }}
            };
        @endif
    </script>
</body>
</html>
