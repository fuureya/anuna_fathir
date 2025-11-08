<?php
include 'db_con.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->status) && isset($data->visit_time)) {
    $id = $data->id;
    $status = $data->status;
    $visit_time = $data->visit_time;

    // Validasi ID
    if (!is_numeric($id)) {
        echo json_encode(['error' => 'ID tidak valid.']);
        exit;
    }

    // Persiapkan dan eksekusi kueri
    $stmt = $conn->prepare("UPDATE reservations SET status = ?, visit_time = ? WHERE id = ?");
    
    if (!$stmt) {
        echo json_encode(['error' => 'Gagal mempersiapkan kueri.']);
        exit;
    }

    $stmt->bind_param("ssi", $status, $visit_time, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Reservasi berhasil diperbarui.']);
    } else {
        echo json_encode(['error' => 'Gagal memperbarui reservasi.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Data tidak lengkap.']);
}

$conn->close();
?>