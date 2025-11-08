<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_con.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = trim($_GET['status']);

    // Validasi status
    $allowed_status = ['confirmed', 'pending', 'rejected'];
    if (in_array($status, $allowed_status, true)) {

        // Pastikan koneksi database aktif
        if (!$conn) {
            die("Koneksi ke database gagal: " . mysqli_connect_error());
        }

        // Update status reservasi
        $update = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        if (!$update) {
            die("Gagal menyiapkan query: " . $conn->error);
        }

        $update->bind_param("si", $status, $id);
        $update->execute();

        if ($update->affected_rows > 0) {
            // Redirect ke halaman manajemen dengan pesan sukses
            header("Location: manage_reservations.php?msg=updated");
            exit();
        } else {
            echo "⚠️ Tidak ada data yang diperbarui. ID mungkin tidak ditemukan.";
        }

        $update->close();
        $conn->close();
    } else {
        echo "❌ Status tidak valid. Gunakan hanya: confirmed, pending, atau rejected.";
    }
} else {
    echo "⚠️ Parameter tidak lengkap. Pastikan URL berisi id dan status.";
}
?>
