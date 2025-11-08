<?php
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['email']; // Ambil email dari session

// Konfigurasi koneksi database
$host = 'localhost'; // Ganti dengan host database Anda
$dbname = 'project_web'; // Ganti dengan nama database Anda
$username_db = 'root'; // Ganti dengan username database Anda
$password_db = ''; // Ganti dengan password database Anda

// Membuat koneksi
try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}

// Ambil data ulasan dari tabel reviews
$query = "SELECT service_quality, book_availability, book_collection FROM reviews"; 
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

    // Normalisasi kalimat
    $review = preg_replace('/[^\w\s]/u', '', strtolower($review));
    $words = preg_split('/\s+/', $review);

    $score = 0;
    $negation = false;

    foreach ($words as $word) {
        if ($word === 'tidak') {
            $negation = true;
        } elseif (in_array($word, $positiveWords) && !$negation) {
            $score++;
        } elseif (in_array($word, $negativeWords) && !$negation) {
            $score--;
        } elseif (in_array($word, $positiveWords) && $negation) {
            $score--;
            $negation = false;
        } elseif (in_array($word, $negativeWords) && $negation) {
            $score++;
            $negation = false;
        } else {
            $negation = false;
        }
    }

    return $score > 0 ? 'Positif' : ($score < 0 ? 'Negatif' : 'Netral');
}

// Hitung jumlah sentimen untuk setiap kategori
$sentimentCounts = [
    'service_quality' => ['Positif' => 0, 'Negatif' => 0, 'Netral' => 0],
    'book_availability' => ['Positif' => 0, 'Negatif' => 0, 'Netral' => 0],
    'book_collection' => ['Positif' => 0, 'Negatif' => 0, 'Netral' => 0],
];

foreach ($reviews as $serviceQuality) {
    $predictedSentimentQuality = analyzeSentiment($serviceQuality);
    $sentimentCounts['service_quality'][$predictedSentimentQuality]++;
}

foreach ($bookAvailability as $availability) {
    $predictedSentimentAvailability = analyzeSentiment($availability);
    $sentimentCounts['book_availability'][$predictedSentimentAvailability]++;
}

foreach ($bookCollection as $collection) {
    $predictedSentimentCollection = analyzeSentiment($collection);
    $sentimentCounts['book_collection'][$predictedSentimentCollection]++;
}

// Menyesuaikan data untuk diagram
$dataForCharts = [
    'service_quality' => [
        $sentimentCounts['service_quality']['Positif'] + 1, // Positif
        $sentimentCounts['service_quality']['Negatif'] + 1, // Negatif
        $sentimentCounts['service_quality']['Netral'] + 1     // Netral
    ],
    'book_availability' => [
        $sentimentCounts['book_availability']['Positif'] + 1,
        $sentimentCounts['book_availability']['Negatif'] + 1,
        $sentimentCounts['book_availability']['Netral'] + 1
    ],
    'book_collection' => [
        $sentimentCounts['book_collection']['Positif'] + 1,
        $sentimentCounts['book_collection']['Negatif'] + 1,
        $sentimentCounts['book_collection']['Netral'] + 1
    ],
];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Review</title>
    <link rel="stylesheet" href="css/admin_dash.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        canvas {
            max-width: 500px;
            margin: 20px auto;
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
    <h2>Diagram Sentimen Ulasan</h2>
    
    <!-- Diagram Sentimen untuk Pelayanan -->
    <h3>Sentimen Ulasan Pelayanan</h3>
    <canvas id="serviceQualityChart"></canvas>
    <script>
        const serviceQualityCtx = document.getElementById('serviceQualityChart').getContext('2d');
        const serviceQualityData = {
            labels: ['Positif', 'Negatif', 'Netral'],
            datasets: [{
                label: 'Jumlah Sentimen',
                data: [<?php echo implode(', ', $dataForCharts['service_quality']); ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(43, 255, 0, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgb(0, 255, 42)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(serviceQualityCtx, {
            type: 'bar',
            data: serviceQualityData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Diagram Sentimen untuk Ketersediaan Buku -->
    <h3>Sentimen Ulasan Ketersediaan Buku</h3>
    <canvas id="bookAvailabilityChart"></canvas>
    <script>
        const bookAvailabilityCtx = document.getElementById('bookAvailabilityChart').getContext('2d');
        const bookAvailabilityData = {
            labels: ['Positif', 'Negatif', 'Netral'],
            datasets: [{
                label: 'Jumlah Sentimen',
                data: [<?php echo implode(', ', $dataForCharts['book_availability']); ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(43, 255, 0, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgb(0, 255, 42)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(bookAvailabilityCtx, {
            type: 'bar',
            data: bookAvailabilityData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Diagram Sentimen untuk Koleksi Buku -->
    <h3>Sentimen Ulasan Koleksi Buku</h3>
    <canvas id="bookCollectionChart"></canvas>
    <script>
        const bookCollectionCtx = document.getElementById('bookCollectionChart').getContext('2d');
        const bookCollectionData = {
            labels: ['Positif', 'Negatif', 'Netral'],
            datasets: [{
                label: 'Jumlah Sentimen',
                data: [<?php echo implode(', ', $dataForCharts['book_collection']); ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(43, 255, 0, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgb(0, 255, 42)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(bookCollectionCtx, {
            type: 'bar',
            data: bookCollectionData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</div>

</body>
</html>