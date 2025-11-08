<?php
// Data buku (biasanya data ini akan diambil dari database)
$books = array(
    1 => array("title" => "Harry Potter and the Philosopher's Stone", "image" => "Harry_potter1.png", "category" => "fiction", "synopsis" => "Synopsis of the first book.", "chapters" => array("Chapter 1: The Boy Who Lived", "Chapter 2: The Vanishing Glass")),
    2 => array("title" => "Harry Potter and the Chamber of Secrets", "image" => "Harry_potter2.png", "category" => "fiction", "synopsis" => "Synopsis of the second book.", "chapters" => array("Chapter 1: The Worst Birthday", "Chapter 2: Dobby's Warning")),
    // tambahkan data buku lainnya sesuai kebutuhan
);

// Ambil ID buku dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Periksa apakah buku dengan ID tersebut ada
if (!isset($books[$id])) {
    echo "<p>Buku tidak ditemukan.</p>";
    exit;
}

$book = $books[$id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
    <link rel="stylesheet" href="detail.css">
    <style>
        .book-detail {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .book-detail img {
            width: 200px;
            height: 300px;
            display: block;
            margin: 0 auto;
        }
        .book-detail h2 {
            text-align: center;
        }
        .book-detail p {
            margin: 10px 0;
        }
        .book-detail ul {
            list-style-type: none;
            padding: 0;
        }
        .book-detail ul li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="book-detail">
        <img src="covers/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <p><strong>Category:</strong> <a href="e_resources.php?category=<?php echo urlencode($book['category']); ?>"><?php echo htmlspecialchars($book['category']); ?></a></p>
        <p><strong>Synopsis:</strong> <?php echo htmlspecialchars($book['synopsis']); ?></p>
        <h3>Chapters</h3>
        <ul>
            <?php foreach ($book['chapters'] as $chapter): ?>
                <li><a href="chapter_detail.php?book_id=<?php echo $id; ?>&chapter=<?php echo urlencode($chapter); ?>"><?php echo htmlspecialchars($chapter); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
