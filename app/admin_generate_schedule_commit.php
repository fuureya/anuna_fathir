<?php
include 'db_con.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Makassar');

$date = $_GET['date'] ?? date('Y-m-d');

// Ambil data reservasi yang sudah dikonfirmasi
$query = "SELECT * FROM reservations WHERE reservation_date = ? AND status = 'confirmed' ORDER BY visit_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) $reservations[] = $row;

// Jalankan algoritma Interval Scheduling
usort($reservations, fn($a, $b) => strcmp($a['visit_time'], $b['visit_time']));
$selected = [];
$last_end = null;
foreach ($reservations as $r) {
    $start = strtotime($r['visit_time']);
    $end = $start + 3600; // 1 jam
    if ($last_end === null || $start >= $last_end) {
        $selected[] = $r;
        $last_end = $end;
    }
}

// Simpan jadwal ke tabel mobile_library_schedule
foreach ($selected as $s) {
    $start_time = date('Y-m-d H:i:s', strtotime($s['visit_time']));
    $end_time = date('Y-m-d H:i:s', strtotime($s['visit_time']) + 3600);
    $insert = $conn->prepare("INSERT INTO mobile_library_schedule (reservation_id, scheduled_date, start_time, end_time) VALUES (?, ?, ?, ?)");
    $insert->bind_param("isss", $s['id'], $date, $start_time, $end_time);
    $insert->execute();
}

echo "<h2>✅ Jadwal berhasil disimpan untuk tanggal $date!</h2>";
echo "<a href='admin_dashboard.php'>Kembali ke Dashboard</a>";
?>
