<?php
// Konfigurasi koneksi database
$host = 'localhost'; // Ganti dengan host database Anda
$dbname = 'project_web'; // Ganti dengan nama database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda

// Membuat koneksi
try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}

// Ambil data ulasan dari database
$query = "SELECT service_quality, book_availability, book_collection FROM reviews"; // Ganti dengan tabel dan kolom yang sesuai
try {
    $stmt = $con->query($query);
    $reviews = [];
    $bookAvailability = [];
    $bookCollection = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = $row['service_quality'];
        $bookAvailability[] = $row['book_availability'];
        $bookCollection[] = $row['book_collection'];
    }
} catch (Exception $e) {
    echo "Kesalahan saat mengambil data: " . $e->getMessage();
    exit;
}

// Kamus kata untuk analisis sentimen
$positiveWords = [
    'memuaskan', 'ramah', 'berpengetahuan', 'berkualitas tinggi', 'baik', 
    'nyaman', 'hebat', 'sangat baik', 'puas'
];
$negativeWords = [
    'buruk', 'jelek', 'tidak puas', 'kecewa', 'masalah', 'tidak ada', 
    'monoton', 'tidak menarik', 'kurang', 'minim', 'sulit', 
    'tidak tersedia', 'mengecewakan', 'biasa saja', 'tidak istimewa', 
    'asal-asalan', 'terbatas', 'kekurangan', 
    'tidak memuaskan', 'menyebalkan', 'parah', 'sangat buruk', 'kurang baik',
];

function analyzeSentiment($review) {
    global $positiveWords, $negativeWords;

    // Normalisasi kalimat (menghapus tanda baca dan mengubah ke huruf kecil)
    $review = preg_replace('/[^\w\s]/u', '', strtolower($review));
    $words = preg_split('/\s+/', $review);

    $score = 0;
    $negation = false;

    foreach ($words as $word) {
        if ($word === 'tidak') {
            $negation = true; // Menangkap negasi
        } elseif (in_array($word, $positiveWords) && !$negation) {
            $score++; // Menambah skor positif
        } elseif (in_array($word, $negativeWords) && !$negation) {
            $score--; // Mengurangi skor negatif
        } elseif (in_array($word, $positiveWords) && $negation) {
            $score--; // Mengurangi pengaruh positif
            $negation = false; // Reset negasi
        } elseif (in_array($word, $negativeWords) && $negation) {
            $score++; // Mengurangi pengaruh negatif
            $negation = false; // Reset negasi
        } else {
            $negation = false; // Reset negasi untuk kata lain
        }
    }

    // Mengembalikan sentimen berdasarkan skor
    if ($score > 0) {
        return 'Positif';
    } elseif ($score < 0) {
        return 'Negatif';
    }
    return 'Netral'; // Mengembalikan 'Netral' jika skor sama dengan 0
}

$positiveCount = 0;
$negativeCount = 0;
$neutralCount = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Review</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            background-color: #0693E3;
            color: #fff;
            width: 250px;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 100%;
            height: auto;
        }
        .divider {
            border: none;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.5);
            margin: 10px 0;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #ddd;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <img src="css/logo.png" alt="Logo" class="logo">
    <hr class="divider">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_user.php">Kelola Pengguna</a></li>
        <li><a href="manage_reservations.php">🗂 Kelola Reservasi</a></li>
        <li><a href="lihat_jadwal.php">📅Lihat Jadwal Keliling</a></li>
        <li><a href="manage_books.php">Kelola Buku</a></li>
        <li><a href="manage_reviews.php">Kelola Ulasan</a></li>
        <li><a href="site_settings.php">Pengaturan Situs</a></li>
        <li><a href="logout.php">Keluar</a></li>
    </ul>
</nav>

<div class="content">
    <h2>Daftar Ulasan dan Sentimen</h2>
    <table>
        <tr>
            <th>Pelayanan</th>
            <th>Ketersediaan Buku</th>
            <th>Koleksi Buku</th>
        </tr>
        <?php
        foreach ($reviews as $index => $serviceQuality) {
            // Analisis sentimen untuk setiap kolom
            $predictedSentimentQuality = analyzeSentiment($serviceQuality);
            $predictedSentimentAvailability = analyzeSentiment($bookAvailability[$index]);
            $predictedSentimentCollection = analyzeSentiment($bookCollection[$index]);

            // Hitung jumlah sentimen
            if ($predictedSentimentQuality === 'Positif') $positiveCount++;
            elseif ($predictedSentimentQuality === 'Negatif') $negativeCount++;
            else $neutralCount++;

            if ($predictedSentimentAvailability === 'Positif') $positiveCount++;
            elseif ($predictedSentimentAvailability === 'Negatif') $negativeCount++;
            else $neutralCount++;

            if ($predictedSentimentCollection === 'Positif') $positiveCount++;
            elseif ($predictedSentimentCollection === 'Negatif') $negativeCount++;
            else $neutralCount++;

            // Tampilkan ulasan jika positif, negatif, atau netral
            echo "<tr>";
            echo "<td>" . htmlspecialchars($serviceQuality) . " (Sentimen: " . $predictedSentimentQuality . ")</td>";
            echo "<td>" . htmlspecialchars($bookAvailability[$index]) . " (Sentimen: " . $predictedSentimentAvailability . ")</td>";
            echo "<td>" . htmlspecialchars($bookCollection[$index]) . " (Sentimen: " . $predictedSentimentCollection . ")</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h3>Statistik Sentimen</h3>
    <p>Jumlah Positif: <?php echo $positiveCount; ?></p>
    <p>Jumlah Negatif: <?php echo $negativeCount; ?></p>
    <p>Jumlah Netral: <?php echo $neutralCount; ?></p>
</div>

</body>
</html>