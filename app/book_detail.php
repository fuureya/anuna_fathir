<?php
session_start();
require 'db_con.php'; // Koneksi ke database

// Ambil id buku dari URL
if (!isset($_GET['id'])) {
    header("Location: book_collection.php");
    exit();
}
$bookId = $_GET['id'];

// Ambil data buku dari database
$query = "SELECT * FROM books WHERE buku_id = ?"; // Menggunakan buku_id
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

// Jika buku tidak ditemukan, kembalikan ke halaman koleksi buku
if (!$book) {
    header("Location: book_collection.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku - <?php echo htmlspecialchars($book['judul']); ?></title>
    <link rel="stylesheet" href="css/detail_buku.css">
</head>
<body>
    <?php
        include 'navbar_login.php';
    ?>

    <div class="book-details">
        <h1><?php echo htmlspecialchars($book['judul']); ?></h1>
        <p><strong><?php echo htmlspecialchars($book['penulis']); ?> - Pengarang</strong></p>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($book['kategori']); ?></p>
        <hr>
        <h4>Deskripsi</h4>
        <p><?php echo htmlspecialchars($book['deskripsi']); ?></p> <!-- Menampilkan deskripsi buku -->
        <hr>
        <p><strong>Penerbit:</strong> <?php echo htmlspecialchars($book['penerbit']); ?></p>
        <p><strong>Tahun Terbit:</strong> <?php echo htmlspecialchars($book['tahun_terbit']); ?></p>
        <p><strong>Jumlah Tersedia:</strong> <?php echo htmlspecialchars($book['jumlah_eksemplar']); ?></p>
        <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['ISBN']); ?></p>
        <hr>
        <a href="book_collection.php">Buku Rekomendasi Lainnya</a>
    </div>

    <div class="book-image">
        <img src="uploads/<?php echo isset($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'default.jpg'; ?>" alt="<?php echo htmlspecialchars($book['judul']); ?>">
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="logo.png" alt="Logo Perpustakaan">
            </div>
            <div class="footer-links">
                <!-- Footer content as before -->
            </div>
        </div>
    </footer>
</body>
</html>