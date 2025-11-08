<?php
session_start(); // Pastikan session dimulai

// Data buku (biasanya data ini akan diambil dari database)
$books = array(
    array("id" => 1, "title" => "Harry Potter and the Philosopher's Stone", "image" => "Harry_potter1.png", "category" => "fiction"),
    array("id" => 2, "title" => "Harry Potter and the Chamber of Secrets", "image" => "Harry_potter2.png", "category" => "fiction"),
    array("id" => 3, "title" => "Harry Potter and the Prisoner of Azkaban", "image" => "Harry_potter3.png", "category" => "history"),
    array("id" => 4, "title" => "Harry Potter and the Goblet of Fire", "image" => "Harry_potter4.png", "category" => "education"),
    array("id" => 5, "title" => "Harry Potter and the Order of the Phoenix", "image" => "Harry_potter5.png", "category" => "education"),
    // tambahkan buku lainnya sesuai kebutuhan
);

// Fungsi untuk menampilkan buku sesuai kategori yang dipilih atau pencarian
function displayBooks($category, $search) {
    global $books;
    $found = false;
    foreach ($books as $book) {
        if (($category === 'all' || $book['category'] === $category) && 
            ($search === '' || stripos($book['title'], $search) !== false)) {
            echo "<div class='book'>";
            echo "<a href='book_detail2.php?id={$book['id']}'>";
            echo "<img src='covers/{$book['image']}' alt='{$book['title']}'>";
            echo "</a>";
            echo "<div class='caption'>{$book['title']}</div>";
            echo "</div>";
            $found = true;
        }
    }
    if (!$found) {
        echo "<p>No books found matching your criteria.</p>";
    }
}

// Ambil kategori yang dipilih dari URL (dari form dropdown)
$category = isset($_GET['category']) ? $_GET['category'] : 'all'; // default kategori 'All'
$search = isset($_GET['search']) ? $_GET['search'] : ''; // default tanpa pencarian
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Collection</title>
    <link rel="stylesheet" href="css/e_book.css">
    <style>
        .book {
            display: inline-block;
            margin: 10px;
            text-align: center;
        }
        .book img {
            width: 150px;
            height: 200px;
        }
        .book .caption {
            margin-top: 5px;
        }
    </style>
</head>
<body>

<?php
include 'navbar_login.php';
?>

    <h1>Book Collection</h1>
    <form action="e_resources.php" method="GET">
        <label for="category">Choose a category:</label>
        <select name="category" id="category">
            <option value="all" <?php if ($category === 'all') echo 'selected'; ?>>All</option>
            <option value="fiction" <?php if ($category === 'fiction') echo 'selected'; ?>>Fiction</option>
            <option value="non-fiction" <?php if ($category === 'non-fiction') echo 'selected'; ?>>Non-Fiction</option>
            <option value="history" <?php if ($category === 'history') echo 'selected'; ?>>History</option>
            <option value="education" <?php if ($category === 'education') echo 'selected'; ?>>Education</option>
        </select>
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Filter">
    </form>

    <h2><?php echo ucfirst($category === 'all' ? 'All' : $category); ?> Books</h2>
    <?php
    // Panggil fungsi untuk menampilkan buku sesuai kategori dan pencarian yang dipilih
    displayBooks($category, $search);
    ?>
</body>
</html>
