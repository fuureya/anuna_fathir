<?php
include 'db_con.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dengan pengecekan aman
    $full_name = $_POST['full_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $reservation_date = $_POST['reservation_date'] ?? '';
    $visit_time = $_POST['visit_time'] ?? '';

    // Pastikan tidak ada field kosong
    if (
        empty($full_name) || empty($category) || empty($occupation) ||
        empty($address) || empty($phone_number) || empty($gender) ||
        empty($reservation_date) || empty($visit_time)
    ) {
        echo "<script>alert('Semua field harus diisi!');history.back();</script>";
        exit;
    }

    // Validasi file upload
    if (!isset($_FILES['request_letter']) || $_FILES['request_letter']['error'] != 0) {
        echo "<script>alert('File surat permohonan wajib diunggah!');history.back();</script>";
        exit;
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['request_letter']['name']);
    $fileTmp = $_FILES['request_letter']['tmp_name'];
    $fileSize = $_FILES['request_letter']['size'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileType !== 'pdf') {
        echo "<script>alert('File harus dalam format PDF!');history.back();</script>";
        exit;
    }

    if ($fileSize > 5 * 1024 * 1024) {
        echo "<script>alert('Ukuran file maksimal 5MB!');history.back();</script>";
        exit;
    }

    $newFileName = uniqid('surat_') . '.' . $fileType;
    $targetPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmp, $targetPath)) {
        echo "<script>alert('Gagal mengunggah file!');history.back();</script>";
        exit;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO reservations 
        (full_name, category, occupation, address, phone_number, gender, reservation_date, visit_time, request_letter, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssssss", $full_name, $category, $occupation, $address, $phone_number, $gender, $reservation_date, $visit_time, $newFileName);

    if ($stmt->execute()) {
    echo "<script>alert('Reservasi berhasil dikirim! Menunggu konfirmasi admin.');window.location='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan reservasi: " . addslashes($stmt->error) . "');history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Perpustakaan Keliling</title>
    <link rel="stylesheet" href="css/reservasii.css">
</head>
<body>
    <div class="reservation-form-container">
        <a href="user_dashboard.php">
            <img src="css/logo.png" alt="Logo Perpustakaan" class="logo">
        </a>
        <h2>Reservasi Perpustakaan Keliling</h2>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="full_name" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label>Kategori (Event/Instansi):</label>
                <input type="text" name="category" placeholder="Contoh: SD/SMP/SMK/Event" required>
            </div>

            <div class="form-group">
                <label>Nama Kegiatan/Instansi:</label>
                <input type="text" name="occupation" placeholder="Contoh: Festival Buku/Hari buku Nasional" required>
            </div>

            <div class="form-group">
                <label>Alamat:</label>
                <input type="text" name="address" placeholder="Alamat Lengkap Instansi/Event Sesuai GoogleMaps" required>
            </div>

            <div class="form-group">
                <label>Nomor Telepon:</label>
                <input type="text" name="phone_number" placeholder="Contoh: 0812xxxxxxx" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select name="gender" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Reservasi:</label>
                <input type="date" name="reservation_date" required>
            </div>

            <div class="form-group">
                <label>Waktu Kunjungan:</label>
                <input type="time" name="visit_time" required>
            </div>

            <div class="form-group">
                <label>Upload Surat Permohonan (PDF, maks. 5MB):</label>
                <input type="file" name="request_letter" accept=".pdf" required>
            </div>

            <button type="submit">Kirim Reservasi</button>
        </form>
    </div>
</body>
</html>
