<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'db_con.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lihat Jadwal Perpustakaan Keliling</title>
<link rel="stylesheet" href="css/manage_user.css">
<style>
    .main-content {
        flex: 1;
        padding: 20px;
        background: #f8f8f8;
    }
    h1 {
        color: #0693E3;
        text-align: center;
        margin-bottom: 20px;
    }
    form {
        text-align: center;
        margin-bottom: 20px;
    }
    form input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    form button {
        background-color: #0693E3;
        color: white;
        padding: 8px 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px;
    }
    form button:hover {
        background-color: #057bbf;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #0693E3;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .btn-back {
        display: inline-block;
        text-decoration: none;
        background-color: #0693E3;
        color: white;
        padding: 8px 16px;
        border-radius: 5px;
        margin-top: 25px;
    }
    .btn-back:hover {
        background-color: #057bbf;
    }
</style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
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

    <!-- Main Content -->
    <div class="main-content">
        <h1>📅 Jadwal Resmi Perpustakaan Keliling</h1>

        <!-- Filter Tanggal -->
        <form method="get">
            <label for="date">Pilih Tanggal:</label>
            <input type="date" id="date" name="date" value="<?php echo $_GET['date'] ?? ''; ?>">
            <button type="submit">Tampilkan</button>
            <a href="lihat_jadwal.php" style="margin-left:10px; color:#0693E3; text-decoration:none;">Tampilkan Semua</a>
        </form>

        <?php
        // Jika admin memilih tanggal, tampilkan jadwal pada tanggal itu
        if (isset($_GET['date']) && !empty($_GET['date'])) {
            $date = $_GET['date'];
            $stmt = $conn->prepare("
                SELECT s.id, s.scheduled_date, s.start_time, s.end_time,
                       r.full_name, r.occupation, r.category
                FROM mobile_library_schedule s
                JOIN reservations r ON s.reservation_id = r.id
                WHERE s.scheduled_date = ?
                ORDER BY s.start_time ASC
            ");
            $stmt->bind_param("s", $date);
        } else {
            // Jika tidak ada tanggal dipilih, tampilkan semua jadwal
            $stmt = $conn->prepare("
                SELECT s.id, s.scheduled_date, s.start_time, s.end_time,
                       r.full_name, r.occupation, r.category
                FROM mobile_library_schedule s
                JOIN reservations r ON s.reservation_id = r.id
                ORDER BY s.scheduled_date DESC, s.start_time ASC
            ");
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemohon</th>
                            <th>Kategori</th>
                            <th>Instansi/Event</th>
                            <th>Tanggal</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody>";
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                        <td>" . htmlspecialchars($row['category']) . "</td>
                        <td>" . htmlspecialchars($row['occupation']) . "</td>
                        <td>" . htmlspecialchars($row['scheduled_date']) . "</td>
                        <td>" . htmlspecialchars($row['start_time']) . "</td>
                        <td>" . htmlspecialchars($row['end_time']) . "</td>
                      </tr>";
                $no++;
            }
            echo "</tbody></table>";
        } else {
            echo "<p style='text-align:center; margin-top:20px; color:red;'>
                    Tidak ada jadwal yang ditemukan.
                  </p>";
        }
        ?>

        <div style="text-align:center;">
            <a href="admin_dashboard.php" class="btn-back">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
