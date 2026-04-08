<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Ditolak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #dc3545;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc3545;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin: 20px 0;
            font-size: 14px;
        }
        .detail-section {
            margin: 25px 0;
        }
        .detail-section h3 {
            color: #dc3545;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-label {
            font-weight: bold;
            width: 180px;
            color: #555;
            font-size: 14px;
        }
        .detail-value {
            flex: 1;
            color: #333;
            font-size: 14px;
        }
        .reason-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .reason-box h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 16px;
        }
        .reason-box .reason-text {
            background: white;
            padding: 15px;
            border-radius: 5px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
            border: 1px solid #ffeaa7;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #0d47a1;
            font-size: 14px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
            color: #0d47a1;
            font-size: 13px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0693E3, #0056b3);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>❌ Reservasi Perpustakaan Ditolak</h1>
            <p>Perpustakaan Keliling Kota Parepare</p>
        </div>

        <p>Yth. <strong>{{ $reservation->full_name }}</strong>,</p>
        <p>Kami informasikan bahwa reservasi perpustakaan Anda telah <strong>DITOLAK</strong> oleh admin.</p>
        
        <center>
            <span class="status-badge">✗ DITOLAK</span>
        </center>

        <div class="detail-section">
            <h3>📋 Detail Reservasi</h3>
            
            <div class="detail-row">
                <div class="detail-label">Nama Pemohon:</div>
                <div class="detail-value">{{ $reservation->full_name }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div class="detail-value">{{ $reservation->email }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Instansi/Organisasi:</div>
                <div class="detail-value">{{ $reservation->occupation }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Kategori:</div>
                <div class="detail-value">{{ ucfirst($reservation->category) }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Tanggal Kunjungan:</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d F Y') }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Waktu Kunjungan:</div>
                <div class="detail-value">
                    @if($reservation->visit_time)
                        {{ \Carbon\Carbon::parse($reservation->visit_time)->format('H:i') }}
                        @if($reservation->end_time)
                            - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                        @endif
                        WITA
                    @else
                        Belum ditentukan
                    @endif
                </div>
            </div>
        </div>

        @if($reservation->rejection_reason)
        <div class="reason-box">
            <h4>📝 Alasan Penolakan:</h4>
            <div class="reason-text">
                {{ $reservation->rejection_reason }}
            </div>
        </div>
        @endif

        <div class="info-box">
            <h4>ℹ️ Apa yang bisa Anda lakukan?</h4>
            <ul>
                <li>Baca alasan penolakan di atas dengan seksama</li>
                <li>Anda dapat membuat <strong>reservasi baru</strong> dengan waktu/tanggal yang berbeda</li>
                <li>Pastikan memenuhi persyaratan yang ditentukan</li>
                <li>Jika ada pertanyaan, hubungi kami melalui kontak di bawah</li>
            </ul>
        </div>

        <center>
            <a href="{{ url('/reservations/create') }}" class="cta-button">
                📝 Buat Reservasi Baru
            </a>
        </center>

        <div class="footer">
            <p><strong>Perpustakaan Umum Daerah Kota Parepare</strong></p>
            <p>Email: perpustakaan@parepare.go.id | Telp: (0421) 12345</p>
            <p>&copy; {{ date('Y') }} Perpustakaan Keliling Kota Parepare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
