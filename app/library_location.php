<?php
session_start();
include 'navbar_login.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lokasi Perpustakaan Panrita Parepare</title>
    <link rel="stylesheet" href="css/lokasi_perpustakaan.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            margin-top: 15px;
        }
        .hours-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .hours-table th, .hours-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .hours-table th {
            background-color: #0693E3;
            color: white;
        }
        .hours-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .contact-info a {
            color: #0693E3;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📍 Lokasi Perpustakaan Keliling Panrita</h1>
        <p>Selamat datang di Perpustakaan Umum Panrita Kota Parepare. Temukan lokasi, jam buka, dan informasi kontak kami di bawah ini.</p>
        
        <h2>Alamat</h2>
        <p>Gedung Panrita Perpustakaan Umum Kota Parepare<br>
           Jl. Jenderal Sudirman No. 50, Kota Parepare, Sulawesi Selatan</p>

        <div class="contact-info">
            <h2>Informasi Kontak</h2>
            <p>📞 Telepon: <a href="https://wa.me/628114128989"target="_blank">+62 811 1412 8989</a></p>
            <p>📧 Email: <a href="mailto:info@perpustakaanparepare.go.id">info@perpustakaanparepare.go.id</a></p>
        </div>
        
        <h2>Jam Operasional</h2>
        <table class="hours-table">
            <tr>
                <th>Hari</th>
                <th>Jam Buka</th>
            </tr>
            <tr><td>Senin - Jumat</td><td>08:00 - 16:00</td></tr>
            <tr><td>Sabtu</td><td>09:00 - 14:00</td></tr>
            <tr><td>Minggu</td><td>Tutup</td></tr>
        </table>

        <h2>Peta Lokasi</h2>
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Koordinat Gedung Panrita Perpustakaan Umum Kota Parepare
        var latitude = -4.0120833;
        var longitude = 119.6207191;

        // Inisialisasi peta
        var map = L.map('map').setView([latitude, longitude], 17);

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker dengan popup informasi
        var popupContent = `
            <b>📚 Gedung Panrita Perpustakaan Umum Kota Parepare</b><br>
            Jl. Jenderal Sudirman No. 50, Kota Parepare<br>
            <b>Jam Buka:</b><br>
            Senin - Jumat: 08.00 - 16.00<br>
            Sabtu: 09.00 - 14.00<br>
            Minggu: Tutup
        `;

        L.marker([latitude, longitude])
            .addTo(map)
            .bindPopup(popupContent)
            .openPopup();
    </script>
</body>
</html>
