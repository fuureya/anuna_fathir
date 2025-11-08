<?php
$servername = "127.0.0.1"; 
$username = "root";
$password = "";
$dbname = "project_web";
// hapus atau ubah port ke 3306
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Koneksi PDO (boleh dipertahankan kalau memang kamu pakai di bagian lain)
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
