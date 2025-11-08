<?php
session_start();
require 'db_con.php';

function displayBooks($category, $search, $genres) {
    global $conn;

    $query = "SELECT * FROM books WHERE 1";

    if (!empty($category) && is_array($category)) {
        $categoryCondition = implode("', '", array_map(function($cat) use ($conn) {
            return mysqli_real_escape_string($conn, $cat);
        }, $category));
        $query .= " AND kategori IN ('$categoryCondition')";
    }

    if ($search !== '') {
        $searchEscaped = mysqli_real_escape_string($conn, $search);
        $query .= " AND judul LIKE '%$searchEscaped%'";
    }

    if (!empty($genres) && is_array($genres)) {
        $genreCondition = implode("', '", array_map(function($gen) use ($conn) {
            return mysqli_real_escape_string($conn, $gen);
        }, $genres));
        $query .= " AND genre IN ('$genreCondition')";
    }

    $result = mysqli_query($conn, $query);

    echo '<div class="books-container">';
    
    if (mysqli_num_rows($result) > 0) {
        while ($book = mysqli_fetch_assoc($result)) {
            echo "<div class='book'>";
            echo "<a href='book_detail.php?id={$book['buku_id']}'>"; // Menggunakan buku_id untuk tautan
            echo "<img src='uploads/{$book['cover_image']}' alt='" . htmlspecialchars($book['judul']) . "'>";
            echo "<div class='book-info'>";
            echo "<h3>" . htmlspecialchars($book['judul']) . "</h3>";
            echo "<p>Pengarang: " . htmlspecialchars($book['penulis']) . "</p>";
            echo "<p>Kategori: " . htmlspecialchars($book['kategori']) . "</p>"; // Menampilkan kategori
            echo "</div>"; // tutup book-info
            echo "</a>"; // tutup tag a
            echo "</div>"; // tutup book
        }
    } else {
        echo "<p>Tidak ada buku yang ditemukan sesuai kriteria pencarian.</p>";
    }

    echo '</div>';

}

// Mengambil input dari query string
$category = isset($_GET['category']) ? (array) $_GET['category'] : []; 
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; 
$genres = isset($_GET['genre']) ? (array) $_GET['genre'] : []; 

include 'navbar_login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Buku</title>
    <link rel="stylesheet" href="css/koleksii.css">
    <script src="koleksi.js" defer></script>
</head>
<body>

<!-- Tombol Pencarian Lanjutan -->
<button type="button" id="advancedSearchBtn">Pencarian Lanjutan</button>

<!-- Pop-up Pencarian Lanjutan -->
<div id="advancedSearchContainer" class="popup-container" style="display: none;">
    <span id="closeAdvancedSearch" class="close-icon">&times;</span>
    <h3>Pencarian Lanjutan</h3>
    <form action="book_collection.php" method="GET">
        <h4>Pilih Kategori</h4>
        <div class="checkbox-group">
            <?php
            // Daftar kategori yang valid
            $valid_categories = [
                'Novel', 'Majalah', 'Kamus', 'Komik', 'Manga', 'Ensiklopedia', 
                'Kitab suci', 'Biografi', 'Naskah', 'Light novel', 'Buku tulis', 
                'Buku gambar', 'Nomik', 'Cergam', 'Antologi', 'Novelet', 
                'Fotografi', 'Karya Ilmiah', 'Atlas', 'Babad'
            ];

            foreach ($valid_categories as $cat) {
                $checked = in_array($cat, $category) ? 'checked' : '';
                echo "<label><input type='checkbox' name='category[]' value='$cat' $checked> $cat</label>";
            }
            ?>
        </div>

        <h4>Pilih Genre</h4>
        <div class="checkbox-group">
            <?php
            // Daftar genre yang valid
            $valid_genres = [
                'Fiksi', 'Non-Fiksi', 'Thriller', 'Misteri', 'Romantis', 
                'Fantasi', 'Sejarah', 'Biografi', 'Sains', 
                'Fiksi Ilmiah', 'Petualangan', 'Horror', 
                'Pendidikan', 'Anak-anak', 'Komedi'
            ];

            foreach ($valid_genres as $genre) {
                $checked = in_array($genre, $genres) ? 'checked' : '';
                echo "<label><input type='checkbox' name='genre[]' value='$genre' $checked> $genre</label>";
            }
            ?>
        </div>
        <div class="apply-filters">
    <button type="submit">Search</button>
</div>
    </form>
</div>

<main>
    <h2>Semua Buku</h2>
    <h5>Tingkatkan literasi membacamu hari ini!</h5>

    <?php
    displayBooks($category, $search, $genres);
    ?>
</main>

<script>
    document.getElementById('advancedSearchBtn').addEventListener('click', function() {
        document.getElementById('advancedSearchContainer').style.display = 'block';
    });

    document.getElementById('closeAdvancedSearch').addEventListener('click', function() {
        document.getElementById('advancedSearchContainer').style.display = 'none';
    });
</script>

</body>
</html>