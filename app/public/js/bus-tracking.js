// ===========================
// Bus Tracking - Main Script
// ===========================

let map = null;
let busMarker = null;
let reservationMarkers = [];
let routeLine = null;
let routeArrows = [];
let animationFrameId = null;

// Configuration
const CONFIG = {
    mapCenter: [-4.0095, 119.6230], // Parepare coordinates
    mapZoom: 13,
    refreshInterval: 10000, // 10 seconds
    animationDuration: 2000, // 2 seconds
    minDistanceToAnimate: 0.0001, // degrees
};

/**
 * Initialize bus tracking
 */
function initBusTracking(reservations, tracking, date) {
    // Initialize map
    initMap();
    
    // Add reservation markers
    addReservationMarkers(reservations);
    
    // Update bus position
    updateBusPosition(tracking);
    
    // Start auto-refresh
    startAutoRefresh(date);
}

/**
 * Initialize Leaflet map
 */
function initMap() {
    map = L.map('map').setView(CONFIG.mapCenter, CONFIG.mapZoom);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);
}

/**
 * Add reservation markers to map
 */
function addReservationMarkers(reservations) {
    reservations.forEach((reservation, index) => {
        if (reservation.latitude && reservation.longitude) {
            const marker = L.marker([reservation.latitude, reservation.longitude], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background: #667eea; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">${index + 1}</div>`,
                    iconSize: [30, 30],
                })
            }).addTo(map);
            
            marker.bindPopup(createPopupContent(reservation, index));
            reservationMarkers.push(marker);
        }
    });
}

/**
 * Create popup content for reservation marker
 */
function createPopupContent(reservation, index) {
    const visitTime = new Date('2000-01-01 ' + reservation.visit_time);
    const endTime = new Date('2000-01-01 ' + reservation.end_time);
    
    return `
        <strong>${reservation.occupation}</strong><br>
        ${reservation.address}<br>
        ⏰ ${visitTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})} - 
        ${endTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})} WITA
    `;
}

/**
 * Smooth animation using linear interpolation
 */
function animateBusMovement(fromLat, fromLng, toLat, toLng, duration = CONFIG.animationDuration) {
    const startTime = Date.now();
    const deltaLat = toLat - fromLat;
    const deltaLng = toLng - fromLng;
    
    // Cancel existing animation
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
    }
    
    function animate() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Ease-in-out function
        const eased = progress < 0.5
            ? 2 * progress * progress
            : -1 + (4 - 2 * progress) * progress;
        
        const currentLat = fromLat + (deltaLat * eased);
        const currentLng = fromLng + (deltaLng * eased);
        
        if (busMarker) {
            busMarker.setLatLng([currentLat, currentLng]);
        }
        
        if (progress < 1) {
            animationFrameId = requestAnimationFrame(animate);
        } else {
            animationFrameId = null;
        }
    }
    
    animate();
}

/**
 * Update bus position on map
 */
function updateBusPosition(tracking) {
    if (!tracking || !tracking.current_latitude || !tracking.current_longitude) {
        return;
    }
    
    const newLat = parseFloat(tracking.current_latitude);
    const newLng = parseFloat(tracking.current_longitude);
    
    if (busMarker) {
        // Animate from current to new position
        const currentLatLng = busMarker.getLatLng();
        const distance = Math.sqrt(
            Math.pow(newLat - currentLatLng.lat, 2) + 
            Math.pow(newLng - currentLatLng.lng, 2)
        );
        
        if (distance > CONFIG.minDistanceToAnimate) {
            animateBusMovement(currentLatLng.lat, currentLatLng.lng, newLat, newLng);
            map.panTo([newLat, newLng], { animate: true, duration: 2.0 });
        }
    } else {
        // Create bus marker
        busMarker = L.marker([newLat, newLng], {
            icon: L.divIcon({
                className: 'bus-marker',
                iconSize: [40, 40],
            })
        }).addTo(map);
        
        busMarker.bindPopup('🚌 Bus Perpustakaan Keliling');
        map.setView([newLat, newLng], 14);
    }
    
    // Draw/update route line
    if (tracking.bus_status === 'on_the_way' && tracking.current_reservation) {
        const destLat = parseFloat(tracking.current_reservation.latitude);
        const destLng = parseFloat(tracking.current_reservation.longitude);
        
        if (destLat && destLng) {
            drawRouteLine(newLat, newLng, destLat, destLng);
        }
    } else {
        clearRouteLine();
    }
}

/**
 * Draw animated route line
 */
function drawRouteLine(fromLat, fromLng, toLat, toLng) {
    clearRouteLine();
    
    routeLine = L.polyline(
        [[fromLat, fromLng], [toLat, toLng]], 
        {
            color: '#f5576c',
            weight: 4,
            opacity: 0.7,
            dashArray: '10, 10',
            dashOffset: '0',
        }
    ).addTo(map);
    
    // Animate dash
    let offset = 0;
    const dashInterval = setInterval(() => {
        if (!routeLine) {
            clearInterval(dashInterval);
            return;
        }
        offset = (offset + 1) % 20;
        routeLine.setStyle({ dashOffset: offset.toString() });
    }, 100);
}

/**
 * Clear route line from map
 */
function clearRouteLine() {
    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }
    
    routeArrows.forEach(arrow => map.removeLayer(arrow));
    routeArrows = [];
}

/**
 * Update UI elements
 */
function updateUI(tracking) {
    if (!tracking) return;
    
    const statusMap = {
        'idle': 'Standby',
        'on_the_way': 'Dalam Perjalanan',
        'arrived': 'Tiba di Lokasi',
        'serving': 'Melayani',
        'finished': 'Selesai',
    };
    
    // Update status text
    const statusEl = document.getElementById('current-status');
    if (statusEl) {
        statusEl.textContent = statusMap[tracking.bus_status] || 'Unknown';
    }
    
    // Update location text
    if (tracking.current_reservation && tracking.current_reservation.address) {
        const locationEl = document.getElementById('current-location');
        if (locationEl) {
            locationEl.textContent = tracking.current_reservation.address;
        }
    }
    
    // Highlight active reservation
    document.querySelectorAll('.reservation-item').forEach(item => {
        item.classList.remove('active');
    });
    
    if (tracking.current_reservation_id) {
        const activeItem = document.getElementById('reservation-' + tracking.current_reservation_id);
        if (activeItem) {
            activeItem.classList.add('active');
        }
    }
}

/**
 * Fetch and update tracking data
 */
async function refreshTracking(date) {
    try {
        const response = await fetch(`/api/bus-tracking/status?date=${date}`);
        const data = await response.json();
        
        updateBusPosition(data.tracking);
        updateUI(data.tracking);
    } catch (error) {
        console.error('Failed to refresh tracking:', error);
    }
}

/**
 * Start auto-refresh timer
 */
function startAutoRefresh(date) {
    setInterval(() => {
        refreshTracking(date);
    }, CONFIG.refreshInterval);
}

/**
 * Change date and reload page
 */
function changeDate() {
    const date = document.getElementById('tracking-date').value;
    window.location.href = `/bus-tracking?date=${date}`;
}
