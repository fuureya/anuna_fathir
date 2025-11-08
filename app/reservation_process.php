<?php
session_start();
include_once 'ids.php';
include('db_con.php');
// Menangkap data dari formulir reservasi
$full_name = $_POST['full_name'] ?? '';
$occupation = $_POST['occupation'] ?? '';
$address = $_POST['address'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$gender = $_POST['gender'] ?? '';
$reservation_date = $_POST['reservation_date'] ?? '';

// Validasi jika semua input telah diisi
if (empty($full_name) || empty($occupation) || empty($address) || empty($phone_number) || empty($gender) || empty($reservation_date)) {
    // Jika ada data yang kosong, tampilkan pesan error dan redirect kembali ke halaman sebelumnya
    $error_message = "Mohon lengkapi semua kolom.";
    header('Location: visitor_reservation.php?error=' . urlencode($error_message));
    exit();
}

// Simpan data reservasi ke dalam array (simulasi database atau implementasi sesuai kebutuhan)
$reservation = array(
    "full_name" => $full_name,
    "occupation" => $occupation,
    "address" => $address,
    "phone_number" => $phone_number,
    "gender" => $gender,
    "reservation_date" => $reservation_date,
    "status" => "Pending" // Status default reservasi
);

// Simpan data reservasi ke dalam file atau database, ini hanya simulasi
// Di sini kita menggunakan file untuk menyimpan data reservasi
$reservations = [];
if (file_exists('reservations.json')) {
    $json_data = file_get_contents('reservations.json');
    $reservations = json_decode($json_data, true);
}

// Tambahkan reservasi baru ke dalam array
$reservations[] = $reservation;

// Simpan array reservasi ke dalam file JSON
file_put_contents('reservations.json', json_encode($reservations, JSON_PRETTY_PRINT));

// Pesan bahwa reservasi berhasil diterima
$message = "Terima kasih, " . $full_name . ". Kami akan mengirimkan pesan konfirmasi setelah reservasi Anda disetujui.";

// Redirect ke halaman login setelah reservasi berhasil
header('Location: index.php?message=' . urlencode($message));
exit();
?>
