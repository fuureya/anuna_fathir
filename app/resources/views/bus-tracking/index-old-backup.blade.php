<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tracking Bus Perpustakaan Keliling</title>
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
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .date-selector {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .date-selector input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .date-selector button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .date-selector button:hover {
            background: #5568d3;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }
        
        .map-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        #map {
            width: 100%;
            height: 500px;
        }
        
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .bus-status-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .bus-status-card h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .status-icon.idle { background: #e0e0e0; }
        .status-icon.on_the_way { background: #fff3cd; }
        .status-icon.arrived { background: #d1ecf1; }
        .status-icon.serving { background: #d4edda; }
        .status-icon.finished { background: #f8d7da; }
        
        .status-info h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .status-info p {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        
        .reservations-list {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .reservations-list h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .reservation-item {
            border-left: 4px solid #667eea;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 10px;
            position: relative;
        }
        
        .reservation-item.active {
            background: #e7f3ff;
            border-left-color: #0693E3;
        }
        
        .reservation-item.completed {
            opacity: 0.6;
            border-left-color: #28a745;
        }
        
        .reservation-number {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #667eea;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .reservation-item h4 {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .reservation-item p {
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .auto-refresh {
            text-align: center;
            padding: 10px;
            background: #e7f3ff;
            border-radius: 5px;
            font-size: 12px;
            color: #0693E3;
        }
        
        .bus-marker {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><text y="32" font-size="40">🚌</text></svg>') center/contain no-repeat;
            width: 40px;
            height: 40px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚌 Tracking Bus Perpustakaan Keliling</h1>
        <p>Pantau lokasi bus perpustakaan keliling secara real-time</p>
    </div>
    
    <div class="container">
        <div class="date-selector">
            <label for="tracking-date" style="font-weight: 600;">Tanggal:</label>
            <input type="date" id="tracking-date" value="{{ $date }}">
            <button onclick="changeDate()">Lihat</button>
        </div>
        
        <div class="main-content">
            <div class="map-container">
                <div id="map"></div>
            </div>
            
            <div class="sidebar">
                <div class="bus-status-card">
                    <h3>📍 Status Bus</h3>
                    <div class="status-item">
                        <div class="status-icon {{ $tracking->bus_status ?? 'idle' }}">
                            {{ $tracking && $tracking->bus_status == 'on_the_way' ? '🚗' : ($tracking && $tracking->bus_status == 'arrived' ? '🏁' : ($tracking && $tracking->bus_status == 'serving' ? '📚' : ($tracking && $tracking->bus_status == 'finished' ? '✅' : '🅿️'))) }}
                        </div>
                        <div class="status-info">
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
                    <div class="status-item">
                        <div class="status-icon" style="background: #0693E3;">📍</div>
                        <div class="status-info">
                            <h4>Lokasi Tujuan</h4>
                            <p id="current-location">{{ $tracking->currentReservation->address }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="auto-refresh">
                        🔄 Auto-refresh setiap 10 detik
                    </div>
                </div>
                
                <div class="reservations-list">
                    <h3>📅 Jadwal Hari Ini</h3>
                    @if($reservations->count() > 0)
                        @foreach($reservations as $index => $reservation)
                        <div class="reservation-item {{ $tracking && $tracking->current_reservation_id == $reservation->id ? 'active' : '' }}" id="reservation-{{ $reservation->id }}">
                            <div class="reservation-number">{{ $index + 1 }}</div>
                            <h4>{{ $reservation->occupation }}</h4>
                            <p>📍 {{ $reservation->address }}</p>
                            <p>⏰ {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WITA</p>
                            <p>👤 {{ $reservation->full_name }}</p>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>Tidak ada jadwal untuk tanggal ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map centered on Parepare, Sulawesi Selatan
        const map = L.map('map').setView([-4.0095, 119.6230], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        let busMarker = null;
        let reservationMarkers = [];
        let currentBusPosition = null;
        let targetBusPosition = null;
        let animationFrameId = null;
        let routeLine = null; // Polyline for route
        let routeArrows = []; // Arrow markers along route
        
        // Add reservation markers
        const reservations = @json($reservations);
        reservations.forEach((reservation, index) => {
            if (reservation.latitude && reservation.longitude) {
                const marker = L.marker([reservation.latitude, reservation.longitude], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background: #667eea; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">${index + 1}</div>`,
                        iconSize: [30, 30]
                    })
                }).addTo(map);
                
                marker.bindPopup(`
                    <strong>${reservation.occupation}</strong><br>
                    ${reservation.address}<br>
                    ⏰ ${new Date('2000-01-01 ' + reservation.visit_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})} - 
                    ${new Date('2000-01-01 ' + reservation.end_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})} WITA
                `);
                
                reservationMarkers.push(marker);
            }
        });
        
        // Smooth animation function using linear interpolation
        function animateBusMovement(fromLat, fromLng, toLat, toLng, duration = 2000) {
            const startTime = Date.now();
            const startLat = fromLat;
            const startLng = fromLng;
            const deltaLat = toLat - fromLat;
            const deltaLng = toLng - fromLng;
            
            // Cancel any existing animation
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            
            function animate() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function (ease-in-out)
                const eased = progress < 0.5
                    ? 2 * progress * progress
                    : -1 + (4 - 2 * progress) * progress;
                
                const currentLat = startLat + (deltaLat * eased);
                const currentLng = startLng + (deltaLng * eased);
                
                if (busMarker) {
                    busMarker.setLatLng([currentLat, currentLng]);
                }
                
                if (progress < 1) {
                    animationFrameId = requestAnimationFrame(animate);
                } else {
                    animationFrameId = null;
                    currentBusPosition = { lat: toLat, lng: toLng };
                }
            }
            
            animate();
        }
        
        // Function to update bus position
        function updateBusPosition(tracking) {
            if (!tracking) return;
            
            if (tracking.current_latitude && tracking.current_longitude) {
                const newLat = parseFloat(tracking.current_latitude);
                const newLng = parseFloat(tracking.current_longitude);
                
                if (busMarker) {
                    // Get current position
                    const currentLatLng = busMarker.getLatLng();
                    const oldLat = currentLatLng.lat;
                    const oldLng = currentLatLng.lng;
                    
                    // Check if position actually changed
                    const distance = Math.sqrt(
                        Math.pow(newLat - oldLat, 2) + Math.pow(newLng - oldLng, 2)
                    );
                    
                    if (distance > 0.0001) { // Only animate if moved significantly
                        // Animate bus movement
                        animateBusMovement(oldLat, oldLng, newLat, newLng, 2000);
                        
                        // Smoothly pan map to new position
                        map.panTo([newLat, newLng], {
                            animate: true,
                            duration: 2.0
                        });
                    }
                } else {
                    // Create bus marker for first time
                    busMarker = L.marker([newLat, newLng], {
                        icon: L.divIcon({
                            className: 'bus-marker',
                            iconSize: [40, 40]
                        })
                    }).addTo(map);
                    
                    busMarker.bindPopup('🚌 Bus Perpustakaan Keliling');
                    currentBusPosition = { lat: newLat, lng: newLng };
                    
                    // Center map on bus
                    map.setView([newLat, newLng], 14);
                }
                
                // Draw route line to destination if bus is on the way
                if (tracking.bus_status === 'on_the_way' && tracking.current_reservation) {
                    const destLat = parseFloat(tracking.current_reservation.latitude);
                    const destLng = parseFloat(tracking.current_reservation.longitude);
                    
                    if (destLat && destLng) {
                        drawRouteLine(newLat, newLng, destLat, destLng);
                    }
                } else {
                    // Remove route line if not on the way
                    clearRouteLine();
                }
            }
        }
        
        // Function to draw route line from bus to destination
        function drawRouteLine(fromLat, fromLng, toLat, toLng) {
            // Clear existing route line
            clearRouteLine();
            
            // Create polyline
            routeLine = L.polyline(
                [[fromLat, fromLng], [toLat, toLng]], 
                {
                    color: '#f5576c',
                    weight: 4,
                    opacity: 0.7,
                    dashArray: '10, 10',
                    dashOffset: '0'
                }
            ).addTo(map);
            
            // Animate dash offset for moving effect
            let offset = 0;
            setInterval(() => {
                if (routeLine) {
                    offset = (offset + 1) % 20;
                    routeLine.setStyle({ dashOffset: offset.toString() });
                }
            }, 100);
            
            // Add arrow marker at destination
            const arrowMarker = L.marker([toLat, toLng], {
                icon: L.divIcon({
                    className: 'route-arrow',
                    html: '<div style="color: #f5576c; font-size: 30px; text-align: center;">📍</div>',
                    iconSize: [30, 30]
                })
            }).addTo(map);
            
            routeArrows.push(arrowMarker);
        }
        
        // Function to clear route line
        function clearRouteLine() {
            if (routeLine) {
                map.removeLayer(routeLine);
                routeLine = null;
            }
            
            routeArrows.forEach(arrow => {
                map.removeLayer(arrow);
            });
            routeArrows = [];
        }
        
        // Initial bus position
        const initialTracking = @json($tracking);
        updateBusPosition(initialTracking);
        
        // Auto-refresh every 10 seconds
        setInterval(async () => {
            try {
                const date = document.getElementById('tracking-date').value;
                const response = await fetch(`/api/bus-tracking/status?date=${date}`);
                const data = await response.json();
                
                updateBusPosition(data.tracking);
                
                // Update status text
                if (data.tracking) {
                    const statusMap = {
                        'idle': 'Standby',
                        'on_the_way': 'Dalam Perjalanan',
                        'arrived': 'Tiba di Lokasi',
                        'serving': 'Melayani',
                        'finished': 'Selesai'
                    };
                    document.getElementById('current-status').textContent = statusMap[data.tracking.bus_status] || 'Unknown';
                    
                    if (data.tracking.current_reservation && data.tracking.current_reservation.address) {
                        const locationEl = document.getElementById('current-location');
                        if (locationEl) {
                            locationEl.textContent = data.tracking.current_reservation.address;
                        }
                    }
                    
                    // Highlight active reservation
                    document.querySelectorAll('.reservation-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    if (data.tracking.current_reservation_id) {
                        const activeItem = document.getElementById('reservation-' + data.tracking.current_reservation_id);
                        if (activeItem) {
                            activeItem.classList.add('active');
                        }
                    }
                }
            } catch (error) {
                console.error('Failed to refresh tracking data:', error);
            }
        }, 10000);
        
        function changeDate() {
            const date = document.getElementById('tracking-date').value;
            window.location.href = `/bus-tracking?date=${date}`;
        }
    </script>
</body>
</html>
