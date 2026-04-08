@extends('layouts.app-public')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">📍 Lokasi Perpustakaan Keliling Panrita</h1>
    <p class="mb-4">Selamat datang di Perpustakaan Umum Panrita Kota Parepare. Temukan lokasi, jam buka, dan informasi kontak kami di bawah ini.</p>

    <h2 class="text-xl font-semibold mb-2">Alamat</h2>
    <p class="mb-4">Gedung Panrita Perpustakaan Umum Kota Parepare<br>
       Jl. Jenderal Sudirman No. 50, Kota Parepare, Sulawesi Selatan</p>

    <h2 class="text-xl font-semibold mb-2">Informasi Kontak</h2>
    <p class="mb-2">📞 Telepon: <a href="https://wa.me/628114128989" target="_blank" class="text-blue-600 underline">+62 811 1412 8989</a></p>
    <p class="mb-4">📧 Email: <a href="mailto:info@perpustakaanparepare.go.id" class="text-blue-600 underline">info@perpustakaanparepare.go.id</a></p>

    <h2 class="text-xl font-semibold mb-2">Jam Operasional</h2>
    <table class="w-full border-collapse mb-4">
        <thead>
            <tr class="bg-blue-600 text-white">
                <th class="border p-2">Hari</th>
                <th class="border p-2">Jam Buka</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-gray-50"><td class="border p-2 text-center">Senin - Jumat</td><td class="border p-2 text-center">08:00 - 16:00</td></tr>
            <tr><td class="border p-2 text-center">Sabtu</td><td class="border p-2 text-center">09:00 - 14:00</td></tr>
            <tr class="bg-gray-50"><td class="border p-2 text-center">Minggu</td><td class="border p-2 text-center">Tutup</td></tr>
        </tbody>
    </table>

    <h2 class="text-xl font-semibold mb-2">Peta Lokasi</h2>
    <div id="map" style="height:400px; width:100%; border-radius:10px;"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var latitude = -4.0120833;
    var longitude = 119.6207191;
    var map = L.map('map').setView([latitude, longitude], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    var popupContent = `<b>📚 Gedung Panrita Perpustakaan Umum Kota Parepare</b><br>
        Jl. Jenderal Sudirman No. 50, Kota Parepare<br>
        <b>Jam Buka:</b><br>
        Senin - Jumat: 08.00 - 16.00<br>
        Sabtu: 09.00 - 14.00<br>
        Minggu: Tutup`;
    L.marker([latitude, longitude]).addTo(map).bindPopup(popupContent).openPopup();
</script>
@endsection
