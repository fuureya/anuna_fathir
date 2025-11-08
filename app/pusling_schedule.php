<?php
// Debug (hapus di production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_con.php';
include 'navbar_login.php';

// Ambil filter pencarian
$place = isset($_GET['place']) ? trim($_GET['place']) : '';
$booker = isset($_GET['booker']) ? trim($_GET['booker']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Query dasar
$sql = "SELECT id, full_name, category, occupation, address, phone_number, gender, reservation_date, status, visit_time, duration_minutes 
        FROM reservations WHERE 1=1";
$params = [];
$types = "";

// Filter alamat
if ($place !== '') {
    $sql .= " AND address LIKE ?";
    $params[] = "%$place%";
    $types .= "s";
}
// Filter pemesan
if ($booker !== '') {
    $sql .= " AND full_name LIKE ?";
    $params[] = "%$booker%";
    $types .= "s";
}
// Filter kategori (jika ingin berdasarkan instansi)
if ($category !== '') {
    $sql .= " AND category LIKE ?";
    $params[] = "%$category%";
    $types .= "s";
}

// Urutkan
$sql .= " ORDER BY reservation_date ASC, visit_time ASC";
$stmt = $conn->prepare($sql);
if (!$stmt) die("Prepare failed: " . $conn->error);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal Pusling | Dashboard User</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1120px;
        margin: 30px auto;
        padding: 0px;
    }

    h1 {
        text-align: center;
        color: #1a202c;
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 30px;
    }

    /* Filter Form */
    .filter-bar {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-bar input[type="text"] {
        padding: 10px 10px;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        width: 150px;
        transition: 0.2s;
    }

    .filter-bar input[type="text"]:focus {
        outline: none;
        border-color: #3182ce;
        box-shadow: 0 0 5px rgba(49,130,206,0.4);
    }

    .filter-bar input[type="submit"] {
        background-color: #3182ce;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 500;
        transition: 0.3s;
    }

    .filter-bar input[type="submit"]:hover {
        background-color: #2b6cb0;
    }

    /* Schedule Cards */
    .schedule-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
        gap: 20px;
    }

    .schedule-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        padding: 20px;
        transition: 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 14px rgba(0,0,0,0.12);
    }

    .schedule-card h2 {
        color: #2b6cb0;
        font-size: 18px;
        margin: 0 0 10px;
    }

    .schedule-card p {
        color: #4a5568;
        font-size: 14px;
        margin: 6px 0;
    }

    .status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        color: white;
        font-size: 13px;
        text-transform: capitalize;
    }

    .status.confirmed { background-color: #38a169; }
    .status.pending { background-color: #dd6b20; }
    .status.rejected { background-color: #e53e3e; } /* merah */
    
    .maps-link {
        display: inline-block;
        margin-top: 10px;
        text-decoration: none;
        background-color: #3182ce;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        transition: 0.2s;
        font-size: 14px;
    }

    .maps-link:hover {
        background-color: #2b6cb0;
    }

    .no-data {
        text-align: center;
        font-size: 15px;
        color: #718096;
        margin-top: 40px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

</style>
</head>
<body>

<div class="container">
    <h1>📅 Jadwal Perpustakaan Keliling (Pusling)</h1>

    <form action="pusling_schedule.php" method="GET" class="filter-bar">
        <input type="text" name="place" placeholder="Cari lokasi..." value="<?php echo htmlspecialchars($place); ?>">
        <input type="text" name="booker" placeholder="Cari pemesan..." value="<?php echo htmlspecialchars($booker); ?>">
        <input type="text" name="category" placeholder="Cari kategori (Instansi)..." value="<?php echo htmlspecialchars($category); ?>">
        <input type="submit" value="🔍 Cari">
    </form>

    <div class="schedule-list">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($row['address']);
                $visit_time = $row['visit_time'] ? date("H:i", strtotime($row['visit_time'])) : 'Belum ditentukan';
                $duration = $row['duration_minutes'] ?? 60;

                // Status logic + ikon
                $status_value = strtolower(trim($row['status']));
                $status_class = in_array($status_value, ['confirmed', 'pending', 'rejected']) ? $status_value : 'pending';
                $icon = $status_value === 'confirmed' ? '✅' : ($status_value === 'pending' ? '🕓' : '❌');

                echo "<div class='schedule-card'>";
                echo "<div class='card-header'>";
                echo "<h2>" . htmlspecialchars($row['reservation_date']) . "</h2>";
                echo "<span class='status {$status_class}'>$icon " . ucfirst($status_value) . "</span>";
                echo "</div>";
                echo "<p><strong>Nama Pemesan:</strong> " . htmlspecialchars($row['full_name']) . "</p>";
                echo "<p><strong>Kategori / Instansi:</strong> " . htmlspecialchars($row['category'] ?? '-') . "</p>";
                echo "<p><strong>Kegiatan:</strong> " . htmlspecialchars($row['occupation']) . "</p>";
                echo "<p><strong>Alamat:</strong> " . htmlspecialchars($row['address']) . "</p>";
                echo "<p><strong>Nomor HP:</strong> " . htmlspecialchars($row['phone_number']) . "</p>";
                echo "<p><strong>Jenis Kelamin:</strong> " . htmlspecialchars($row['gender']) . "</p>";
                echo "<p><strong>Waktu Kunjungan:</strong> " . htmlspecialchars($visit_time) . " (Durasi: " . htmlspecialchars($duration) . " menit)</p>";

                if ($status_value === 'rejected') {
                    echo "<div class='rejected-msg'>❌ Reservasi ini telah <strong>ditolak oleh admin</strong>.</div>";
                }

                echo "<a href='" . htmlspecialchars($mapsUrl) . "' target='_blank' class='maps-link'>📍 Lihat Lokasi di Google Maps</a>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-data'>Tidak ada data reservasi ditemukan.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>