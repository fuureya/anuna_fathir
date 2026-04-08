@extends('layouts.admin')

@section('title', 'Scan QR Code')

@push('styles')
<style>
    .scan-container {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    .scanner-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .manual-input {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 10px;
        margin-top: 30px;
    }
    .manual-input h3 {
        color: #0693E3;
        margin-bottom: 20px;
    }
    .input-group {
        display: flex;
        gap: 10px;
        max-width: 500px;
        margin: 0 auto;
    }
    .input-group input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
    }
    .input-group button {
        padding: 12px 30px;
        background: #0693E3;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }
    .input-group button:hover {
        background: #0056b3;
    }
    .info-box {
        background: #E3F2FD;
        border-left: 4px solid #0693E3;
        padding: 15px;
        margin: 20px 0;
        text-align: left;
    }
</style>
@endpush

@section('content')
<div class="scan-container">
    <h1 style="color: #0693E3; margin-bottom: 10px;">🔍 Verifikasi Reservasi</h1>
    <p style="color: #666; margin-bottom: 30px;">Scan QR Code atau masukkan ID reservasi manual</p>

    <div class="scanner-section">
        <h3>📱 Scan QR Code</h3>
        <p style="color: #666; margin-bottom: 20px;">Gunakan aplikasi scanner QR code di HP/tablet Anda</p>
        
        <div class="info-box">
            <h4 style="margin: 0 0 10px 0; color: #0693E3;">Cara Scan QR Code:</h4>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Minta pengunjung menunjukkan QR code dari email</li>
                <li>Buka aplikasi kamera/scanner QR di HP</li>
                <li>Arahkan kamera ke QR code</li>
                <li>Klik link yang muncul untuk verifikasi otomatis</li>
            </ol>
        </div>
    </div>

    <div class="manual-input">
        <h3>⌨️ Input Manual</h3>
        <p style="color: #666; margin-bottom: 20px;">Atau masukkan ID reservasi secara manual</p>
        
        <form method="GET" action="{{ route('qr.verify', ['id' => 0]) }}" id="verifyForm">
            <div class="input-group">
                <input 
                    type="number" 
                    name="id" 
                    id="reservationId"
                    placeholder="Masukkan ID Reservasi (contoh: 123)" 
                    required
                    min="1">
                <button type="submit">Verifikasi</button>
            </div>
        </form>
    </div>

    <div style="margin-top: 40px; padding: 20px; background: #FFF3CD; border-radius: 8px; text-align: left;">
        <h4 style="margin: 0 0 10px 0; color: #856404;">💡 Tips:</h4>
        <ul style="margin: 0; padding-left: 20px; color: #856404;">
            <li>QR code berisi ID reservasi yang dapat langsung diverifikasi</li>
            <li>Pastikan koneksi internet stabil</li>
            <li>Cek status reservasi: <strong>CONFIRMED</strong> = boleh masuk</li>
            <li>Cross-check dengan identitas pengunjung</li>
        </ul>
    </div>
</div>

<script>
document.getElementById('verifyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('reservationId').value;
    if (id) {
        window.location.href = "{{ url('/verify') }}/" + id;
    }
});
</script>
@endsection
