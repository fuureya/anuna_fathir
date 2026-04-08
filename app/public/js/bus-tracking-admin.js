// ===========================
// Bus Tracking - Admin Control
// ===========================

let map = null;
let selectedReservationId = null;
let selectedLocation = { lat: null, lng: null };
let currentMarker = null;
let simulationMode = false;
let simulationInterval = null;
let currentSimulatedPosition = null;

// Configuration
const CONFIG = {
    mapCenter: [-4.0095, 119.6230], // Parepare
    mapZoom: 13,
    simulationSteps: 50,
    simulationInterval: 500, // ms
};

/**
 * Initialize admin control
 */
function initAdminControl(tracking, reservations) {
    // Set selected reservation if exists
    if (tracking && tracking.current_reservation_id) {
        selectedReservationId = tracking.current_reservation_id;
        
        if (tracking.currentReservation) {
            selectedLocation = {
                lat: tracking.currentReservation.latitude || 0,
                lng: tracking.currentReservation.longitude || 0,
            };
        }
    }
    
    // Set simulated position
    if (tracking && tracking.current_latitude && tracking.current_longitude) {
        currentSimulatedPosition = {
            lat: parseFloat(tracking.current_latitude),
            lng: parseFloat(tracking.current_longitude),
        };
    }
}

/**
 * Initialize map
 */
function initMap() {
    if (!map) {
        map = L.map('map').setView(CONFIG.mapCenter, CONFIG.mapZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);
    }
}

/**
 * Toggle map visibility
 */
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

/**
 * Select reservation
 */
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
                iconSize: [40, 40],
            })
        }).addTo(map);
        
        map.setView([lat, lng], 15);
    }
}

/**
 * Toggle simulation mode
 */
function toggleSimulation() {
    const checkbox = document.getElementById('simulate-movement');
    const info = document.getElementById('simulate-info');
    checkbox.checked = !checkbox.checked;
    simulationMode = checkbox.checked;
    
    if (simulationMode) {
        info.classList.add('active');
        showAlert('🎮 Mode simulasi aktif - Bus akan bergerak virtual menuju tujuan', 'info');
    } else {
        info.classList.remove('active');
        stopSimulation();
    }
}

/**
 * Start simulation
 */
function startSimulation() {
    if (!simulationMode || !selectedLocation.lat || !selectedLocation.lng) {
        return;
    }
    
    stopSimulation();
    
    // Set starting position
    if (!currentSimulatedPosition) {
        currentSimulatedPosition = { lat: CONFIG.mapCenter[0], lng: CONFIG.mapCenter[1] };
    }
    
    const targetLat = selectedLocation.lat;
    const targetLng = selectedLocation.lng;
    const steps = CONFIG.simulationSteps;
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
        
        // Update server
        try {
            const date = document.getElementById('tracking-date').value;
            await fetch('/admin/bus-tracking/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    date: date,
                    reservation_id: selectedReservationId,
                    status: 'on_the_way',
                    latitude: currentSimulatedPosition.lat,
                    longitude: currentSimulatedPosition.lng,
                }),
            });
        } catch (error) {
            console.error('Simulation error:', error);
        }
    }, CONFIG.simulationInterval);
}

/**
 * Stop simulation
 */
function stopSimulation() {
    if (simulationInterval) {
        clearInterval(simulationInterval);
        simulationInterval = null;
    }
}

/**
 * Update bus status
 */
async function updateStatus(status) {
    // Disable buttons
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.disabled = true;
    });
    
    showAlert('Mengupdate status...', 'info');
    
    try {
        let latitude = null;
        let longitude = null;
        
        if (simulationMode) {
            // Use simulated position
            if (currentSimulatedPosition) {
                latitude = currentSimulatedPosition.lat;
                longitude = currentSimulatedPosition.lng;
            } else {
                latitude = CONFIG.mapCenter[0];
                longitude = CONFIG.mapCenter[1];
                currentSimulatedPosition = { lat: latitude, lng: longitude };
            }
            
            // Start simulation on "berangkat"
            if (status === 'on_the_way') {
                startSimulation();
            } else {
                stopSimulation();
            }
        } else {
            // Try GPS
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                date: date,
                reservation_id: selectedReservationId,
                status: status,
                latitude: latitude,
                longitude: longitude,
            }),
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
                'finished': '✅ Selesai',
            };
            
            const badge = document.getElementById('current-status-badge');
            badge.textContent = statusMap[status];
            badge.className = 'status-badge ' + status;
            
            document.getElementById('last-update').textContent = 'Baru saja';
            
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

/**
 * Show alert
 */
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

/**
 * Change date
 */
function changeDate() {
    const date = document.getElementById('tracking-date').value;
    window.location.href = `/admin/bus-tracking?date=${date}`;
}
