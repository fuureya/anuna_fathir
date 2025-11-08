<?php
include 'db_con.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data)) {
    echo json_encode(['error' => 'Tidak ada data yang diberikan.']);
    exit;
}

// Simpan log optimalisasi
$stmt = $conn->prepare("INSERT INTO optimization_logs (reservation_ids, optimized_route, created_at) VALUES (?, ?, NOW())");

if (!$stmt) {
    echo json_encode(['error' => 'Gagal mempersiapkan kueri.']);
    exit;
}

// Mengonversi data ke format string jika diperlukan
$reservation_ids = implode(',', array_column($data, 'id'));
$optimized_route = json_encode($data); // Atau format lain yang diinginkan

$stmt->bind_param("ss", $reservation_ids, $optimized_route);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Log optimalisasi berhasil disimpan.']);
} else {
    echo json_encode(['error' => 'Gagal menyimpan log optimalisasi.']);
}

$stmt->close();
?>