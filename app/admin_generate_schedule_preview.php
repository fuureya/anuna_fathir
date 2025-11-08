<?php
include 'db_con.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Makassar');

$date = $_GET['date'] ?? date('Y-m-d');

$query = "SELECT * FROM reservations WHERE reservation_date = ? AND status = 'confirmed' ORDER BY visit_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

// Urutkan berdasarkan waktu mulai
usort($reservations, fn($a, $b) => strcmp($a['visit_time'], $b['visit_time']));

// Proses Interval Scheduling
$selected = [];
$last_end = null;
foreach ($reservations as $r) {
    $start = strtotime($r['visit_time']);
    $end = $start + (60 * 60); // durasi default 1 jam

    if ($last_end === null || $start >= $last_end) {
        $selected[] = $r;
        $last_end = $end;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Preview Jadwal Perpustakaan Keliling</title>
<style>
body { font-family: Arial; background: #f9f9f9; padding: 20px; }
table { border-collapse: collapse; width: 100%; background: white; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background: #0693E3; color: white; }
</style>
</head>
<body>
<h2>📅 Preview Jadwal Perpustakaan Keliling (<?= htmlspecialchars($date) ?>)</h2>
<table>
<tr>
    <th>Nama</th>
    <th>Kategori</th>
    <th>Instansi/Event</th>
    <th>Waktu</th>
</tr>
<?php foreach ($selected as $s): ?>
<tr>
    <td><?= htmlspecialchars($s['full_name']) ?></td>
    <td><?= htmlspecialchars($s['category']) ?></td>
    <td><?= htmlspecialchars($s['occupation']) ?></td>
    <td><?= htmlspecialchars($s['visit_time']) ?> - <?= date('H:i', strtotime($s['visit_time']) + 3600) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
