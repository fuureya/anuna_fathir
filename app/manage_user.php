<?php
session_start();

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['userid']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Kode untuk menghubungkan dengan database
include('db_con.php'); // Pastikan koneksi database sudah ada

// Ambil daftar pengguna dari database
$sql = "SELECT id, fullname, email, role FROM users"; // Pastikan nama tabel sesuai dengan yang ada di database
$result = $conn->query($sql);

// Cek apakah query berhasil
if ($result === false) {
    die("Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            <h1 style="color: #0693E3; font-family: 'Mulish', sans-serif; text-align: center;">PENGELOLAAN PENGGUNA</h1>
            <!-- User Table --> 
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Loop untuk menampilkan data pengguna
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td><a href='view_user.php?id=" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['fullname']) . "</a></td>"; // Link ke halaman edit dan lihat user
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                            echo "<td>
                                    <form action='edit_user.php' method='get' style='display:inline;'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <button type='submit' class='btn edit-btn'>Edit</button>
                                    </form>
                                    <form id='deleteForm' action='delete_user.php' method='get' style='display:inline;'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <button type='button' class='btn delete-btn' onclick='showPopup(" . htmlspecialchars($row['id']) . ")'>Delete</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users found</td></tr>";
                    }
                    ?>
                     <div id="popup" class="popup">
                    <div class="popup-content">
                        <span class="close" onclick="closePopup()">&times;</span>
                        <p>Anda yakin menghapus buku ini?</p>
                        <button onclick="confirmDelete()">Ya</button>
                        <button onclick="closePopup()">Tidak</button>
                    </div>
                </div>

                <script>
                    var deleteForm;

                    function showPopup() {
                        deleteForm = document.getElementById('deleteForm');
                        document.getElementById("popup").style.display = "block";
                    }

                    function closePopup() {
                        document.getElementById("popup").style.display = "none";
                    }

                    function confirmDelete() {
                        deleteForm.submit(); // Submit the form if confirmed
                    }
                </script>
                <style>
                            /* Pop-up Styles */
                            .popup {
                                display: none; /* Hidden by default */
                                position: fixed;
                                z-index: 1;
                                left: 0;
                                top: 0;
                                width: 100%;
                                height: 100%;
                                overflow: auto;
                                background-color: rgba(0, 0, 0, 0.7);
                                border-radius: 5px; /* Black background with opacity */
                            }

                            .popup-content {
                                background-color: #fff; /* Mengubah warna latar belakang pop-up */
                                margin: 15% auto;
                                padding: 20px;
                                border: 1px solid #888;
                                width: 90%; /* Lebar pop-up lebih kecil agar lebih menarik */
                                max-width: 400px; /* Membatasi lebar maksimum pop-up */
                                border-radius: 8px; /* Membuat sudut pop-up melengkung */
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Efek bayangan pada pop-up */
                                text-align: center;
                                animation: fadeIn 0.3s; /* Animasi saat muncul */
                            }

                            @keyframes fadeIn {
                                from { opacity: 0; }
                                to { opacity: 1; }
                            }

                            .close {
                                color: #aaa;
                                float: right;
                                font-size: 28px;
                                font-weight: bold;
                            }

                            .close:hover,
                            .close:focus {
                                color: black;
                                text-decoration: none;
                                cursor: pointer;
                            }
                            .popup-content button:first-child { /* Tombol Yes */
                                background-color: #4CAF50; /* Hijau */
                                color: white;
                                border-radius: 2px;
                                
                            }

                            .popup-content button:first-child:hover {
                                background-color: #45a049; /* Warna lebih gelap saat hover */
                                transform: scale(1.05); /* Efek zoom saat hover */
                                border-radius: 2px;
                            }

                            .popup-content button:last-child { /* Tombol No */
                                background-color: #f44336; /* Merah */
                                color: white;
                                border-radius: 2px;

                            }

                            .popup-content button:last-child:hover {
                                background-color: #d32f2f; /* Warna lebih gelap saat hover */
                                transform: scale(1.05); /* Efek zoom saat hover */
                            }
                    </style>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Tutup koneksi database
$conn->close();
?>