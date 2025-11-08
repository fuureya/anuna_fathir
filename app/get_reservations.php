<?php
include 'db_con.php';
header('Content-Type: application/json');

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // Validasi format tanggal
    if (!preg_match('/\d{4}-\d{2}-\d{2}/', $date)) {
        echo json_encode(['error' => 'Format tanggal tidak valid']);
        exit;
    }

    // Persiapkan dan eksekusi kueri
    $stmt = $conn->prepare("SELECT id, full_name, occupation, reservation_date, latitude, longitude, audience_category, visit_time, status FROM reservations WHERE reservation_date = ?");
    
    if (!$stmt) {
        echo json_encode(['error' => 'Gagal mempersiapkan kueri.']);
        exit;
    }

    $stmt->bind_param("s", $date);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $reservations = $result->fetch_all(MYSQLI_ASSOC);

        // Batasi hasil ke maksimum 4 reservasi
        if (count($reservations) > 4) {
            $reservations = array_slice($reservations, 0, 4);
            echo json_encode(['warning' => 'Hanya menampilkan 4 reservasi pertama.', 'reservations' => $reservations]);
        } else {
            echo json_encode($reservations);
        }
    } else {
        echo json_encode(['error' => 'Gagal menjalankan kueri.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Tidak ada tanggal yang diberikan.']);
}
?>