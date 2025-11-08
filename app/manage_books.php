<?php
session_start();
require 'db_con.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku</title>
    <link rel="stylesheet" href="css/manage_user.css"> <!-- gaya sama seperti halaman admin lainnya -->
    <style>
        .main-content { flex: 1; padding: 20px; background: #f8f8f8; }
        h1 { color: #0693E3; text-align: center; margin-bottom: 15px; }
        .add-book-btn {
            display: inline-block;
            background-color: #0693E3;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 15px;
        }
        .add-book-btn:hover { background-color: #057bbf; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th { background-color: #0693E3; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }

        .btn {
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn { background: #4CAF50; color: white; }
        .edit-btn:hover { background: #3e9145; }
        .delete-btn { background: #f44336; color: white; }
        .delete-btn:hover { background: #d32f2f; }

        /* Pop-up Styles */
        .popup {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .popup-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .popup-content button {
            margin: 8px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
        .popup-content .yes-btn { background-color: #4CAF50; }
        .popup-content .no-btn { background-color: #f44336; }
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
        <h1>Kelola Buku</h1>
        <p style="text-align:center;">Selamat datang, <b><?php echo htmlspecialchars($username); ?></b>!</p>

        <a href="add_book.php" class="add-book-btn">+ Tambah Buku Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Jumlah</th>
                    <th>ISBN</th>
                    <th>Kategori</th>
                    <th>Audien</th>
                    <th>Cover</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM books");
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = htmlspecialchars($row['buku_id']);
                        echo "<tr>
                            <td>{$id}</td>
                            <td>" . htmlspecialchars($row['judul']) . "</td>
                            <td>" . htmlspecialchars($row['penulis']) . "</td>
                            <td>" . htmlspecialchars($row['penerbit']) . "</td>
                            <td>" . htmlspecialchars($row['tahun_terbit']) . "</td>
                            <td>" . htmlspecialchars($row['jumlah_eksemplar']) . "</td>
                            <td>" . htmlspecialchars($row['ISBN']) . "</td>
                            <td>" . htmlspecialchars($row['kategori']) . "</td>
                            <td>" . ($row['audience_category'] ?: '-') . "</td>
                            <td><img src='uploads/" . htmlspecialchars($row['cover_image']) . "' alt='Cover' width='40'></td>
                            <td>" . htmlspecialchars($row['deskripsi']) . "</td>
                            <td>
                                <form action='edit_book.php' method='get' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$id}'>
                                    <button type='submit' class='btn edit-btn'>Edit</button>
                                </form>
                                <button class='btn delete-btn' onclick='showPopup({$id})'>Hapus</button>
                                <form id='deleteForm{$id}' action='delete_book.php' method='get'>
                                    <input type='hidden' name='id' value='{$id}'>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>Tidak ada buku ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Popup Konfirmasi -->
<div id="popup" class="popup">
    <div class="popup-content">
        <p>Yakin ingin menghapus buku ini?</p>
        <button class="yes-btn" onclick="confirmDelete()">Ya</button>
        <button class="no-btn" onclick="closePopup()">Tidak</button>
    </div>
</div>

<script>
let deleteTarget = null;

function showPopup(id) {
    deleteTarget = document.getElementById('deleteForm' + id);
    document.getElementById('popup').style.display = 'block';
}
function closePopup() {
    document.getElementById('popup').style.display = 'none';
    deleteTarget = null;
}
function confirmDelete() {
    if (deleteTarget) deleteTarget.submit();
}
</script>

</body>
</html>
