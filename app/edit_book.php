<?php
session_start();
require 'db_con.php'; // Include koneksi database

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['email']; // Ambil email atau username dari session

// Get book ID from URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
} else {
    header("Location: manage_books.php");
    exit();
}

// Fetch book details from the database
$query = "SELECT * FROM books WHERE buku_id = $book_id";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 1) {
    $book = mysqli_fetch_assoc($result);
} else {
    echo "Book not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated data from the form
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = $_POST['tahun_terbit'];
    $jumlah_eksemplar = $_POST['jumlah_eksemplar'];
    $ISBN = mysqli_real_escape_string($conn, $_POST['ISBN']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $audience_category = mysqli_real_escape_string($conn, $_POST['audience_category']); // Ambil kategori audiens
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']); // Ambil deskripsi

    // Handle cover image upload
    if ($_FILES['cover_image']['name']) {
        $cover_image = $_FILES['cover_image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cover_image);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file);
    } else {
        $cover_image = $book['cover_image']; // Keep the old cover if no new one is uploaded
    }

    // Update book data in the database
    $update_query = "UPDATE books SET 
        judul = '$judul',
        penulis = '$penulis',
        penerbit = '$penerbit',
        tahun_terbit = $tahun_terbit,
        jumlah_eksemplar = $jumlah_eksemplar,
        ISBN = '$ISBN',
        kategori = '$kategori',
        audience_category = '$audience_category', -- Update kategori audiens
        cover_image = '$cover_image',
        deskripsi = '$deskripsi' 
        WHERE buku_id = $book_id";

    if (mysqli_query($conn, $update_query)) {
        header("Location: manage_books.php"); // Redirect to manage books page
        exit();
    } else {
        echo "Error updating book: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="css/edit_book.css">
</head>
<body>
    <div class="container">
        <h1>Edit Book</h1>
        <form action="edit_book.php?id=<?php echo $book['buku_id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="judul">Title:</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($book['judul']); ?>" required><br>
            
            <label for="penulis">Author:</label>
            <input type="text" name="penulis" value="<?php echo htmlspecialchars($book['penulis']); ?>" required><br>
            
            <label for="penerbit">Publisher:</label>
            <input type="text" name="penerbit" value="<?php echo htmlspecialchars($book['penerbit']); ?>" required><br>
            
            <label for="tahun_terbit">Year:</label>
            <input type="number" name="tahun_terbit" value="<?php echo $book['tahun_terbit']; ?>" required><br>
            
            <label for="jumlah_eksemplar">Copies:</label>
            <input type="number" name="jumlah_eksemplar" value="<?php echo $book['jumlah_eksemplar']; ?>" required><br>
            
            <label for="ISBN">ISBN:</label>
            <input type="text" name="ISBN" value="<?php echo htmlspecialchars($book['ISBN']); ?>" required><br>
            
            <label for="kategori">Category:</label>
            <input type="text" name="kategori" value="<?php echo htmlspecialchars($book['kategori']); ?>" required><br>

            <label for="audience_category">Audience Category:</label>
            <select id="audience_category" name="audience_category" required>
                <option value="">Select Audience Category</option>
                <option value="1-5" <?php echo ($book['audience_category'] == '1-5') ? 'selected' : ''; ?>>Ages 1-5</option>
                <option value="6-12" <?php echo ($book['audience_category'] == '6-12') ? 'selected' : ''; ?>>Ages 6-12</option>
                <option value="13-17" <?php echo ($book['audience_category'] == '13-17') ? 'selected' : ''; ?>>Ages 13-17</option>
                <option value="18-25" <?php echo ($book['audience_category'] == '18-25') ? 'selected' : ''; ?>>Ages 18-25</option>
                <option value="Umum" <?php echo ($book['audience_category'] == 'Umum') ? 'selected' : ''; ?>>General</option>
            </select><br>

            <label for="deskripsi">Description:</label>
            <textarea name="deskripsi" rows="4" required><?php echo htmlspecialchars($book['deskripsi']); ?></textarea><br>

            <label for="cover_image">Cover Image (Leave empty to keep current):</label>
            <input type="file" name="cover_image" accept="image/*"><br>
            
            <button type="submit">Update Book</button>
        </form>
    </div>
</body>
</html>