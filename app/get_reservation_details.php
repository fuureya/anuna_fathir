<?php
include 'db_con.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi ID
    if (!is_numeric($id)) {
        echo json_encode(['error' => 'ID tidak valid.']);
        exit;
    }

    // Persiapkan dan eksekusi kueri
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    
    if (!$stmt) {
        echo json_encode(['error' => 'Gagal mempersiapkan kueri.']);
        exit;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $reservation = $result->fetch_assoc();

        if ($reservation) {
            echo json_encode($reservation);
        } else {
            echo json_encode(['error' => 'Reservasi tidak ditemukan.']);
        }
    } else {
        echo json_encode(['error' => 'Gagal menjalankan kueri.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Tidak ada ID yang diberikan.']);
}

$conn->close();
?>