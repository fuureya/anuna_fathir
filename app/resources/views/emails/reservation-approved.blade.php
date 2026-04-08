<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Disetujui</title>
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
            border-bottom: 3px solid #0693E3;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0693E3;
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
            background: #28a745;
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
            color: #0693E3;
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
        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        .qr-section h3 {
            color: #0693E3;
            margin-bottom: 15px;
        }
        .qr-section img {
            max-width: 250px;
            height: auto;
            border: 3px solid #0693E3;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }
        .qr-section p {
            margin-top: 15px;
            color: #666;
            font-size: 13px;
        }
        .verification-code {
            background: #E3F2FD;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            border-left: 4px solid #0693E3;
        }
        .verification-code strong {
            font-size: 20px;
            color: #0693E3;
            letter-spacing: 2px;
        }
        .info-box {
            background: #FFF3CD;
            border-left: 4px solid #FFC107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 14px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
            color: #856404;
            font-size: 13px;
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
            <h1>🎉 Reservasi Perpustakaan Disetujui</h1>
            <p>Perpustakaan Keliling Kota Parepare</p>
        </div>

        <p>Yth. <strong>{{ $reservation->full_name }}</strong>,</p>
        <p>Kami dengan senang hati memberitahukan bahwa reservasi perpustakaan Anda telah <strong>DISETUJUI</strong>.</p>
        
        <center>
            <span class="status-badge">✓ DISETUJUI</span>
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
                <div class="detail-label">Nomor Telepon:</div>
                <div class="detail-value">{{ $reservation->phone_number }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Instansi/Organisasi:</div>
                <div class="detail-value">{{ $reservation->occupation }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Kategori Layanan:</div>
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
                            - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} WITA
                            <br><span style="color: #666; font-size: 13px;">(Durasi maksimal: 2 jam)</span>
                        @else
                            WITA
                        @endif
                    @else
                        Belum ditentukan
                    @endif
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Alamat Lokasi:</div>
                <div class="detail-value">{{ $reservation->address }}</div>
            </div>
        </div>

        @if($reservation->start_time)
        <div class="detail-section">
            <h3>📊 Informasi Antrian FCFS (First Come First Served)</h3>
            
            <div style="background: linear-gradient(135deg, #f3e7ff 0%, #e9d5ff 100%); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; border-left: 4px solid #7c3aed;">
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">POSISI ANTRIAN</div>
                        <div style="font-size: 28px; font-weight: bold; color: #7c3aed;">
                            @if($reservation->queue_position == 1)
                                🏆 #{{ $reservation->queue_position }}
                            @elseif($reservation->queue_position == 2)
                                🥈 #{{ $reservation->queue_position }}
                            @elseif($reservation->queue_position == 3)
                                🥉 #{{ $reservation->queue_position }}
                            @else
                                #{{ $reservation->queue_position }}
                            @endif
                        </div>
                        <div style="font-size: 11px; color: #7c3aed; margin-top: 5px; font-weight: 600;">
                            @if($reservation->queue_position == 1)
                                Prioritas Pertama!
                            @elseif($reservation->queue_position <= 3)
                                Top 3 Prioritas
                            @else
                                Urutan ke-{{ $reservation->queue_position }}
                            @endif
                        </div>
                    </div>
                    
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; border-left: 4px solid #3b82f6;">
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">WAKTU TUNGGU</div>
                        <div style="font-size: 28px; font-weight: bold; color: #3b82f6;">{{ $reservation->waiting_time ?? 0 }}<span style="font-size: 16px;">m</span></div>
                        <div style="font-size: 11px; color: #666; margin-top: 5px;">
                            @if($reservation->waiting_time == 0)
                                <span style="color: #10b981; font-weight: 600;">Langsung dilayani!</span>
                            @elseif($reservation->waiting_time < 60)
                                {{ $reservation->waiting_time }} menit
                            @else
                                {{ floor($reservation->waiting_time / 60) }} jam {{ $reservation->waiting_time % 60 }} menit
                            @endif
                        </div>
                    </div>
                    
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; border-left: 4px solid #10b981;">
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">TOTAL WAKTU PROSES</div>
                        <div style="font-size: 28px; font-weight: bold; color: #10b981;">{{ $reservation->turnaround_time ?? 0 }}<span style="font-size: 16px;">m</span></div>
                        <div style="font-size: 11px; color: #666; margin-top: 5px;">
                            @if($reservation->turnaround_time >= 60)
                                {{ floor($reservation->turnaround_time / 60) }} jam {{ $reservation->turnaround_time % 60 }} menit
                            @else
                                {{ $reservation->turnaround_time }} menit
                            @endif
                        </div>
                    </div>
                    
                    <div style="background: white; padding: 15px; border-radius: 8px; text-align: center; border-left: 4px solid #06b6d4;">
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">WAKTU MULAI LAYANAN</div>
                        <div style="font-size: 24px; font-weight: bold; color: #06b6d4;">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</div>
                        <div style="font-size: 11px; color: #666; margin-top: 5px;">
                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #7c3aed;">
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.6;">
                        <strong style="color: #7c3aed;">💡 Informasi:</strong> Jadwal Anda telah dioptimalkan menggunakan algoritma 
                        <strong>FCFS (First Come First Served)</strong>. Urutan antrian ditentukan berdasarkan waktu pendaftaran 
                        untuk memastikan pelayanan yang adil dan efisien. Waktu mulai layanan di atas adalah waktu yang telah 
                        dihitung oleh sistem untuk memberikan layanan terbaik kepada Anda.
                    </p>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Anda adalah pengunjung ke:</div>
                <div class="detail-value">
                    <strong style="color: #7c3aed; font-size: 16px;">
                        @if($reservation->queue_position == 1)
                            🏆 #{{ $reservation->queue_position }} (PERTAMA)
                        @elseif($reservation->queue_position == 2)
                            🥈 #{{ $reservation->queue_position }} (KEDUA)
                        @elseif($reservation->queue_position == 3)
                            🥉 #{{ $reservation->queue_position }} (KETIGA)
                        @else
                            #{{ $reservation->queue_position }}
                        @endif
                    </strong>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Estimasi Waktu Tunggu:</div>
                <div class="detail-value">
                    <strong style="color: #3b82f6;">
                        @if($reservation->waiting_time == 0)
                            ⚡ Tidak ada - Langsung dilayani!
                        @elseif($reservation->waiting_time < 30)
                            {{ $reservation->waiting_time }} menit (Cepat)
                        @elseif($reservation->waiting_time < 60)
                            {{ $reservation->waiting_time }} menit (Sedang)
                        @else
                            {{ floor($reservation->waiting_time / 60) }} jam {{ $reservation->waiting_time % 60 }} menit
                        @endif
                    </strong>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Waktu Mulai Layanan:</div>
                <div class="detail-value">
                    <strong style="color: #06b6d4; font-size: 16px;">
                        🚀 {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} WITA
                    </strong>
                    <br><span style="color: #666; font-size: 13px;">Harap datang 15 menit sebelum waktu ini</span>
                </div>
            </div>
        </div>
        @endif

        <div class="verification-code">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">Kode Verifikasi Reservasi:</p>
            <strong>{{ $reservation->id }}-{{ strtoupper(substr(md5($reservation->email), 0, 6)) }}</strong>
        </div>

        <div class="qr-section">
            <h3>🔍 QR Code Verifikasi</h3>
            <p>Tunjukkan QR Code ini saat tiba di perpustakaan</p>
            
            <div style="background: #E3F2FD; padding: 30px; border-radius: 10px; text-align: center; margin: 20px 0;">
                <svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto;">
                    <rect width="200" height="200" fill="#0693E3" opacity="0.1" rx="10"/>
                    <path d="M100 40 L160 100 L100 160 L40 100 Z" fill="#0693E3" opacity="0.3"/>
                    <circle cx="100" cy="100" r="30" fill="none" stroke="#0693E3" stroke-width="3"/>
                    <text x="100" y="110" text-anchor="middle" font-size="48" fill="#0693E3" font-weight="bold">QR</text>
                </svg>
                <p style="margin-top: 15px; color: #0693E3; font-weight: bold; font-size: 16px;">
                    📎 QR Code terlampir sebagai file attachment
                </p>
                <p style="color: #666; font-size: 13px; margin: 10px 0;">
                    Lihat file: <strong>QR_Code_Reservasi.png</strong> di attachment email ini
                </p>
            </div>
            
            <p><small><strong>Cara menggunakan:</strong> Download attachment "QR_Code_Reservasi.png" dan tunjukkan saat datang ke perpustakaan, atau gunakan kode verifikasi di atas</small></p>
        </div>

        <div class="info-box">
            <h4>⚠️ Informasi Penting:</h4>
            <ul>
                @if($reservation->start_time && $reservation->queue_position)
                <li><strong style="color: #7c3aed;">Anda berada di urutan #{{ $reservation->queue_position }}</strong> dalam antrian hari ini</li>
                <li>Harap datang <strong>15 menit sebelum waktu mulai layanan ({{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} WITA)</strong></li>
                @else
                <li>Harap datang <strong>15 menit sebelum</strong> waktu kunjungan yang dijadwalkan</li>
                @endif
                <li>Tunjukkan <strong>QR Code</strong> atau <strong>Kode Verifikasi</strong> kepada petugas</li>
                <li>Bawa <strong>identitas resmi</strong> (KTP/SIM/Kartu Pelajar)</li>
                <li>Jika ada perubahan jadwal, hubungi kami minimal <strong>2 hari sebelumnya</strong></li>
                <li>Reservasi akan <strong>otomatis dibatalkan</strong> jika tidak hadir tanpa pemberitahuan</li>
                @if($reservation->waiting_time > 0)
                <li><strong style="color: #3b82f6;">Estimasi waktu tunggu: {{ $reservation->waiting_time < 60 ? $reservation->waiting_time . ' menit' : floor($reservation->waiting_time / 60) . ' jam ' . ($reservation->waiting_time % 60) . ' menit' }}</strong></li>
                @endif
            </ul>
        </div>

        <div class="footer">
            <p><strong>Perpustakaan Umum Daerah Kota Parepare</strong></p>
            <p>Email: perpustakaan@parepare.go.id | Telp: (0421) 12345</p>
            <p>&copy; {{ date('Y') }} Perpustakaan Keliling Kota Parepare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
