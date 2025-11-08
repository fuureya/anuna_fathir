<?php
session_start();
include 'db_con.php'; // Pastikan jalur ini benar

$error_message = ''; // Untuk menyimpan pesan kesalahan

// Memproses ulasan jika formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari formulir
    $service_quality = $_POST['service_quality'] ?? '';
    $book_availability = $_POST['book_availability'] ?? '';
    $book_collection = $_POST['book_collection'] ?? '';

    // Validasi input
    if (empty($service_quality) || empty($book_availability) || empty($book_collection)) {
        $error_message = "Mohon lengkapi semua kolom.";
    } else {
        // Query untuk menyimpan data ke database
        $sql = "INSERT INTO reviews (service_quality, book_availability, book_collection)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $pdo->errorInfo()[2]);
        }

        // Eksekusi pernyataan
        try {
            $stmt->execute([$service_quality, $book_availability, $book_collection]);
            echo "<script type='text/javascript'>
                    alert('Ulasan Anda telah berhasil disimpan!');
                    window.location.href = 'user_dashboard.php'; // Redirect ke dashboard setelah klik OK
                </script>";
        } catch (PDOException $e) {
            $error_message = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berikan Ulasan</title>
    <link rel="stylesheet" href="css/ulasan.css"> <!-- Pastikan jalur ini benar -->
</head>
<body>


<?php if (!empty($error_message)): ?>
    <div style="color: red;"><?php echo $error_message; ?></div>
<?php endif; ?>

<form action="give_review.php" method="POST">
    
<h2>Berikan Ulasan Anda</h2>
    <label for="service_quality">Kualitas Pelayanan:</label><br>
    <textarea id="service_quality" name="service_quality" rows="4" required></textarea><br><br>

    <label for="book_availability">Ketersediaan Buku:</label><br>
    <textarea id="book_availability" name="book_availability" rows="4" required></textarea><br><br>

    <label for="book_collection">Koleksi Buku:</label><br>
    <textarea id="book_collection" name="book_collection" rows="4" required></textarea><br><br>

    <input type="submit" value="Kirim Ulasan">
</form>

</body>
</html>