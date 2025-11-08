<?php
session_start();

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include('db_con.php');

// Ambil data reservasi
$query = "SELECT * FROM reservations ORDER BY reservation_date DESC";
$result = $conn->query($query);

// Jika query gagal
if ($result === false) {
    die("Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Reservasi</title>
<link rel="stylesheet" href="css/manage_user.css">
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
            <h1 style="color:#0693E3; text-align:center;">🗂 Kelola Reservasi Perpustakaan Keliling</h1>
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
<div style="background-color:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:15px;text-align:center;">
    ✅ Status reservasi berhasil diperbarui dan email telah dikirim (jika diterima).
</div>
<?php endif; ?>

            <!-- Tombol Preview & Commit Jadwal -->
            <form method="get" action="admin_generate_schedule_preview.php" target="_blank" style="text-align:center;">
                <label>Pilih Tanggal:</label>
                <input type="date" name="date" required>
                <button type="submit" class="btn">Preview Jadwal</button>
            </form>

            <form method="get" action="admin_generate_schedule_commit.php" target="_blank" onsubmit="return confirm('Yakin simpan jadwal ini?')" style="text-align:center; margin-bottom:20px;">
                <label>Pilih Tanggal:</label>
                <input type="date" name="date" required>
                <button type="submit" class="btn">Commit Jadwal</button>
            </form>

            <!-- Tabel Reservasi -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemohon</th>
                        <th>Kategori</th>
                        <th>Instansi/Event</th>
                        <th>Tanggal Reservasi</th>
                        <th>Waktu Kunjungan</th>
                        <th>Surat Permohonan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['occupation']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['reservation_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['visit_time']) . "</td>";
                        echo "<td>";
                        if (!empty($row['request_letter'])) {
                            echo "<a href='uploads/" . htmlspecialchars($row['request_letter']) . "' target='_blank' class='btn view-btn'>Lihat PDF</a>";
                        } else {
                            echo "<span style='color:red;'>Belum Upload</span>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($row['status'] == 'pending') {
                            echo "<span style='color:orange;'>Menunggu</span>";
                        } elseif ($row['status'] == 'confirmed') {
                            echo "<span style='color:green;'>Diterima</span>";
                        } else {
                            echo "<span style='color:red;'>Ditolak</span>";
                        }
                        echo "</td>";

                        // Tombol Aksi
                        echo "<td>";
                        if ($row['status'] == 'pending') {
                            echo "<button type='button' class='btn accept-btn' onclick=\"showPopup('konfirmasi_reservasi.php?id=" . $row['id'] . "&status=confirmed')\">Terima</button> ";
                            echo "<button type='button' class='btn reject-btn' onclick=\"showPopup('konfirmasi_reservasi.php?id=" . $row['id'] . "&status=rejected')\">Tolak</button>";

                        } else {
                            echo "<span style='color:#666;'>Selesai</span>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='9'>Belum ada data reservasi.</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <!-- Popup Konfirmasi -->
            <div id="popup" class="popup">
                <div class="popup-content">
                    <span class="close" onclick="closePopup()">&times;</span>
                    <p>Apakah Anda yakin ingin mengubah status reservasi ini?</p>
                    <button id="confirmYes">Ya</button>
                    <button onclick="closePopup()">Tidak</button>
                </div>
            </div>

            <div style="text-align:center; margin-top:25px;">
                <a href="admin_dashboard.php" class="btn">⬅ Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        let actionUrl = "";

        function showPopup(url) {
            actionUrl = url;
            document.getElementById("popup").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }

        document.getElementById("confirmYes").addEventListener("click", function() {
            window.location.href = actionUrl;
        });
    </script>

    <style>
        /* Popup Styles */
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
        }
        .popup-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover {
            color: black;
            cursor: pointer;
        }
        .popup-content button {
            padding: 8px 16px;
            border: none;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
        }
        .popup-content button:first-child {
            background-color: #4CAF50;
        }
        .popup-content button:last-child {
            background-color: #f44336;
        }

        /* Tombol Aksi di Tabel */
        .btn {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            background-color: #0693E3;
        }
        .btn:hover { background-color: #057bbf; }
        .accept-btn { background-color: #28a745; }
        .reject-btn { background-color: #dc3545; }
        .view-btn { background-color: #007bff; }
    </style>
</body>
</html>

<?php
$conn->close();
?>
