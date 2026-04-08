<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Jadwal Perpustakaan Keliling</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background: white; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #0693E3; color: white; }
    </style>
    </head>
<body>
<h2>📅 Preview Jadwal Perpustakaan Keliling ({{ $date }})</h2>
<table>
    <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Instansi/Event</th>
        <th>Waktu</th>
    </tr>
    @foreach ($selected as $item)
        <tr>
            <td>{{ $item['reservation']->full_name }}</td>
            <td>{{ $item['reservation']->category }}</td>
            <td>{{ $item['reservation']->occupation }}</td>
            <td>{{ $item['start']->format('H:i') }} - {{ $item['end']->format('H:i') }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
