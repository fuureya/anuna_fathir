<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Reservasi Perpustakaan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 30px 50px;
            line-height: 1.8;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #000;
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            color: #000;
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .field {
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        .field label {
            font-weight: bold;
            color: #000;
            display: block;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .field-value {
            color: #000;
            font-size: 10px;
            padding: 3px 0;
            border-bottom: 1px dotted #666;
        }
        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin: 15px 0;
        }
        .info-box h3 {
            margin: 0 0 8px 0;
            color: #000;
            font-size: 11px;
            font-weight: bold;
        }
        .info-box ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 3px 0;
            font-size: 10px;
            color: #000;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #000;
            font-size: 9px;
            color: #000;
        }
        .example {
            padding: 3px 0;
            margin-top: 2px;
            font-size: 10px;
            color: #333;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoPath = public_path('logo.png');
        @endphp
        @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" alt="Logo" style="height: 50px; margin-bottom: 10px;">
        @endif
        <h1>Formulir Reservasi Perpustakaan</h1>
        <p>Perpustakaan Keliling & Gedung</p>
        <p style="font-size: 10px; margin-top: 8px;">Panduan Pengisian Formulir Reservasi</p>
    </div>

    <div class="info-box">
        <h3>PETUNJUK PENGISIAN:</h3>
        <ul>
            <li>Isi semua kolom dengan data yang valid dan akurat</li>
            <li>Untuk jenis layanan: pilih "Perpustakaan Keliling" atau "Perpustakaan Gedung"</li>
            <li>Tanggal kunjungan harus format: YYYY-MM-DD (contoh: 2025-11-15)</li>
            <li>Waktu kunjungan format: HH:MM (contoh: 09:00 atau 14:30)</li>
            <li>Jumlah pengunjung harus berupa angka (minimal 1 orang)</li>
            <li>Pastikan mengunggah surat resmi dalam format PDF (maksimal 2MB)</li>
        </ul>
    </div>

    <div class="section">
        <div class="section-title">I. Informasi Pemohon</div>
        
        <div class="field">
            <label>1. Nama Lengkap Pemohon:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Contoh: Ahmad Rizki Pratama</div>
        </div>

        <div class="field">
            <label>2. Email:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Contoh: ahmad.rizki@email.com</div>
        </div>

        <div class="field">
            <label>3. Nomor Telepon:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Contoh: 081234567890</div>
        </div>

        <div class="field">
            <label>4. Nama Instansi/Organisasi:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Contoh: SDN 01 Jakarta Selatan / Komunitas Baca Indonesia</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">II. Detail Reservasi</div>
        
        <div class="field">
            <label>1. Jenis Layanan:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Pilih: Perpustakaan Keliling / Perpustakaan Gedung</div>
        </div>

        <div class="field">
            <label>2. Tanggal Kunjungan:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Format: YYYY-MM-DD, Contoh: 2025-11-15</div>
        </div>

        <div class="field">
            <label>3. Waktu Kunjungan:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Format: HH:MM, Contoh: 09:00 atau 14:30</div>
        </div>

        <div class="field">
            <label>4. Jumlah Pengunjung:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Contoh: 25 orang</div>
        </div>

        <div class="field">
            <label>5. Alamat Lokasi Kunjungan:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Alamat lengkap termasuk kelurahan, kecamatan, kota, dan kode pos</div>
        </div>

        <div class="field">
            <label>6. Tujuan Kunjungan:</label>
            <div class="field-value">...........................................................................................</div>
            <div class="field-value">...........................................................................................</div>
            <div class="example">Jelaskan keperluan atau tujuan reservasi perpustakaan</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">III. Dokumen Pendukung</div>
        
        <div class="field">
            <label>Surat Resmi (Format PDF):</label>
            <div class="info-box">
                <h3>Persyaratan Dokumen:</h3>
                <ul>
                    <li>Format file: PDF (.pdf)</li>
                    <li>Ukuran maksimal: 2 MB</li>
                    <li>Berisi kop surat resmi instansi</li>
                    <li>Ditandatangani oleh pejabat berwenang</li>
                    <li>Mencantumkan tujuan dan detail kunjungan</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="info-box">
        <h3>INFORMASI PENTING:</h3>
        <ul>
            <li>Reservasi akan diproses maksimal 3 hari kerja</li>
            <li>Anda akan menerima email konfirmasi setelah reservasi disetujui</li>
            <li>Untuk Perpustakaan Keliling, mohon pilih tanggal minimal 7 hari dari sekarang</li>
            <li>Hubungi kami jika ada pertanyaan: perpustakaan@email.com atau 021-12345678</li>
        </ul>
    </div>

    <div class="footer">
        <p>Formulir Reservasi Perpustakaan - {{ date('Y') }}</p>
        <p>Dokumen ini adalah panduan pengisian formulir reservasi online</p>
    </div>
</body>
</html>
