<?php
session_start();
require 'db_con.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("Location: loginn.php"); // Arahkan ke login jika belum login
    exit();
}

$user_email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/user.css">
    <script>
        let currentIndex = 0;
        const intervalTime = 3000; // Waktu dalam milidetik (3000 = 3 detik)

        function moveCarousel(direction) {
            const container = document.querySelector('.books-container');
            const books = document.querySelectorAll('.book');
            const totalBooks = books.length;

            if (direction === 'next') {
                currentIndex = (currentIndex + 1) % totalBooks; // Pindah ke buku selanjutnya
            } else {
                currentIndex = (currentIndex - 1 + totalBooks) % totalBooks; // Pindah ke buku sebelumnya
            }

            const moveAmount = -currentIndex * (books[0].offsetWidth + 20); // Hitung jumlah yang harus dipindahkan
            container.style.transform = `translateX(${moveAmount}px)`; // Menggerakkan kontainer
        }

        function startAutoMove() {
            setInterval(() => {
                moveCarousel('next'); // Pindah ke buku selanjutnya
            }, intervalTime);
        }

        window.onload = startAutoMove;

        function toggleDropdown() {
            const dropdown = document.getElementById('user-settings-dropdown');
            dropdown.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn, .dropbtn img')) {
                const dropdowns = document.getElementsByClassName('dropdown-content');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</head>
<body>
    <header>
        <a href="user_dashboard.php">
            <img src="logo.png" alt="Library Logo" class="logo">
        </a>
        <div class="header-links">
            <li class="search-form">
                <form action="search.php" method="get">
                    <input type="text" name="book_title" placeholder="Cari Judul/Pengarang/ISBN...">
                </form>
            </li>
            <a href="view_profile.php">
                <img src="profile.png" alt="View Profile" class="nav-icon">
            </a>
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">
                    <img src="gear_icon.png" alt="User Settings">
                </button>
                <div id="user-settings-dropdown" class="dropdown-content">
                    <a href="user_settings.php">Settings</a>
                    <a href="delete_account.php">Delete Account</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="book_collection.php">Koleksi Buku</a></li>
            <li><a href="visitor_reservation.php">Reservasi Kunjungan</a></li>
            <li><a href="e_resources.php">E-Resources</a></li>
            <li><a href="literacy_agenda.php">Agenda Literasi</a></li>
            <li><a href="pusling_schedule.php">Jadwal Pusling</a></li>
            <li><a href="library_location.php">Lokasi Perpustakaan</a></li>
        </ul>
    </nav>
    <!-- Ikon Melayang untuk Ulasan -->
    <!-- Gambar Melayang untuk Ulasan -->
    <div class="floating-icon" onclick="window.location.href='give_review.php';">
        <img src="css/ulasan.png" alt="Beri Ulasan">
    </div>
    <section>
        <div class="overlay-container">
        <h3 class="recommendation-title" style="text-align: center; font-weight: bold; font-size: 30px; margin-bottom: 2px; ">Rekomendasi Buku</h3>
        <h4 class="recommendation-subtitle" style="text-align: center; font-weight: bold; font-size: 20px;">Tingkatkan literasi membacamu hari ini!</h4>
            <div class="carousel">
                <div class="books-container">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM books");

                    if (mysqli_num_rows($result) > 0) {
                        while ($book = mysqli_fetch_assoc($result)) {
                            echo "<div class='book'>";
                            echo "<a href='book_detail.php?id=" . htmlspecialchars($book['buku_id']) . "'>";
                            echo "<img src='uploads/" . htmlspecialchars($book['cover_image']) . "' alt='" . htmlspecialchars($book['judul']) . "' class='book-cover'>";
                            echo "<div class='book-info'>";
                            echo "<h3 class='book-title'>" . htmlspecialchars($book['judul']) . "</h3>";
                            echo "<p class='book-author'>Pengarang: " . htmlspecialchars($book['penulis']) . "</p>";
                            echo "<p class='book-category'>Kategori: " . htmlspecialchars($book['kategori']) . "</p>";
                            echo "</div>"; // tutup book-info
                            echo "</a>"; // tutup tag a
                            echo "</div>"; // tutup book
                        }
                    } else {
                        echo "<p>Tidak ada buku yang ditemukan sesuai kriteria pencarian.</p>";
                    }
                    ?>
        </div>
    </section>
    <section class="main-topic-section">
        <h3 class="main-topic-title">TOPIK UTAMA</h3>
        <div class="news-container">
            <div class="news-box">
                <img src="berita1.jpg" alt="News Image 1">
                <div class="news-content">
                    <h4>ASN Disperpus Parepare Lolos Final Pustakawan Berprestasi Tingkat Nasional</h4>
                    <div class="news-meta">HS - 22 MEI 2024</div>
                    <p>DISPERPUSPAREPARE, NEWS - Pustakawan Dinas Perpustakaan Kota Parepare, Hery, S.I.P., M.I.P. lolos melaju ke babak final dalam ajang pemilihan Pustakawan Berprestasi Tingkat Nasional Tahun...</p>
                    <a href="news_detail.php?id=1">Baca Selengkapnya</a>
                </div>
            </div>
            <div class="news-box">
                <img src="news_image2.jpg" alt="News Image 2">
                <div class="news-content">
                    <h4>Judul Berita 2</h4>
                    <p>Ringkasan berita 2 yang menarik dan informatif...</p>
                    <a href="news_detail.php?id=2">Baca Selengkapnya</a>
                </div>
            </div>
            <div class="news-box">
                <img src="news_image3.jpg" alt="News Image 3">
                <div class="news-content">
                    <h4>Judul Berita 3</h4>
                    <p>Ringkasan berita 3 yang menarik dan informatif...</p>
                    <a href="news_detail.php?id=3">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>
    <section class="ebook-container">
        <div class="ebook-content">
            <h3>Perpustakaan Berbasis Layanan Digital (PELITA)</h3>
            <div class="icon-container">
                <div class="ebook-icon">
                    <img src="ebook.png" alt="Icon E-Book">
                </div>
                <div class="file-icon">
                    <img src="file.png" alt="Icon file">
                </div>
            </div>
        </div>
    </section>
    <section class="service-center">
        <div class="text-layanan">
            <h3 style="color: black; margin-top: 50px; margin-left: 60px; font-size: 30px; font-weight: 900;">Menemukan Masalah?</h3>
            <h3 style="color: #002C61; margin-top: -9px; margin-left: 60px; font-size: 30px; font-weight: 900;">Kami Siap Membantu</h3>
        </div>
        <div class="service-box">
            <h3 style="margin-top: 10px;">Hubungi Kami</h3>
        </div>
        <div class="service-box-faq">
            <h3 style="color: #002C61; margin-top: 10px;">FAQ</h3>
        </div>
    </section>
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="logo.png" alt="Logo Perpustakaan">
            </div>
            <div class="footer-links">
                <div class="footer-info">
                    <h4>Informasi & Bantuan</h4>
                    <ul>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="whatsapp://send?phone=6281234567890">WhatsApp</a></li>
                        <li><a href="tentang_kami.php">Tentang Kami</a></li>
                        <li><a href="mailto:info@domain.com">Email</a></li>
                        <li><a href="kebijakan_privasi.php">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="footer-additional">
                    <h4>Syarat & Ketentuan</h4>
                    <ul>
                        <li><a href="keanggotaan.php">Keanggotaan</a></li>
                        <li><a href="peminjaman.php">Peminjaman</a></li>
                        <li><a href="pengembalian.php">Pengembalian</a></li>
                    </ul>
                </div>
                <div class="footer-shortcuts">
                    <h4>Pintasan</h4>
                    <ul>
                        <li><a href="akun.php">Akun</a></li>
                        <li><a href="buku_favorit.php">Buku Favorit</a></li>
                        <li><a href="riwayat_pinjaman.php">Riwayat Pinjaman</a></li>
                        <li><a href="aktifitas.php">Aktifitas</a></li>
                        <li><a href="riwayat_ulasan.php">Riwayat Ulasan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>