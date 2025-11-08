<?php
session_start();
require 'db_con.php'; // Include koneksi database

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['email']; // Ambil email atau username dari session

// Daftar kategori yang valid
$valid_categories = [
    'Novel', 'Majalah', 'Kamus', 'Komik', 'Manga', 'Ensiklopedia',
    'Kitab suci', 'Biografi', 'Naskah', 'Light novel', 'Buku tulis',
    'Buku gambar', 'Nomik', 'Cergam', 'Antologi', 'Novelet',
    'Fotografi', 'Karya Ilmiah', 'Atlas', 'Babad'
];

// Periksa apakah kategori yang dipilih valid
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = $_POST['tahun_terbit'];
    $jumlah_eksemplar = $_POST['jumlah_eksemplar'];
    $ISBN = mysqli_real_escape_string($conn, $_POST['ISBN']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $audience_category = mysqli_real_escape_string($conn, $_POST['audience_category']); // Ambil kategori audiens
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']); // Ambil deskripsi

    // Validasi kategori
    if (!in_array($kategori, $valid_categories)) {
        echo "Kategori yang dipilih tidak valid.";
        exit();
    }

    // Upload cover image
    $cover_image = $_FILES['cover_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($cover_image);

    // Periksa apakah file berhasil diunggah
    if ($_FILES['cover_image']['error'] == 0) {
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            echo "Gagal mengunggah gambar.";
            exit();
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah gambar.";
        exit();
    }

    // Insert into database
    $query = "INSERT INTO books (judul, penulis, penerbit, tahun_terbit, jumlah_eksemplar, ISBN, kategori, audience_category, cover_image, deskripsi)
    VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$jumlah_eksemplar', '$ISBN', '$kategori', '$audience_category', '$cover_image', '$deskripsi')";
    
    if (mysqli_query($conn, $query)) {
        // Redirect back to manage books page
        header("Location: manage_books.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="css/add_book.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Buku</h1>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>

        <!-- Add Book Form -->
        <form action="add_book.php" method="POST" enctype="multipart/form-data">
            <label for="judul">Judul</label>
            <input type="text" name="judul" required><br>

            <label for="penulis">Penulis</label>
            <input type="text" name="penulis" required><br>

            <label for="penerbit">Penerbit</label>
            <input type="text" name="penerbit" required><br>

            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="number" name="tahun_terbit" required><br>

            <label for="jumlah_eksemplar">Jumlah Tersedia</label>
            <input type="number" name="jumlah_eksemplar" required><br>

            <label for="ISBN">ISBN</label>
            <input type="text" name="ISBN" required><br>

            <label for="kategori">Kategori</label>
            <select name="kategori" required>
                <option value="Novel">Novel</option>
                <option value="Majalah">Majalah</option>
                <!-- Tambahkan kategori lain di sini -->
            </select><br>

            <!-- Dropdown untuk kategori audiens -->
            <div class="form-group">
                <label for="audience_category">Kategori Audiens</label>
                <select id="audience_category" name="audience_category" required>
                    <option value="">Pilih Kategori Audiens</option>
                    <option value="1-5">Usia 1-5</option>
                    <option value="6-12">Usia 6-12</option>
                    <option value="13-17">Usia 13-17</option>
                    <option value="18-25">Usia 18-25</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>

            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" rows="4" required></textarea><br>

            <label for="cover_image">Cover buku</label>
            <input type="file" name="cover_image" accept="image/*" required><br>

            <button type="submit">Tambahkan Buku</button>
        </form>
    </div>
</body>
</html>