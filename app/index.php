<?php
require 'db_con.php'; // Include koneksi database

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Homepage</title>
    <link rel="stylesheet" href="css/tamu.css">
    <script>
         // Ambil pesan dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');

        // Tampilkan popup jika terdapat pesan
        if (message) {
            window.addEventListener('DOMContentLoaded', (event) => {
                document.getElementById('popup').style.display = 'block';
                setTimeout(function() {
                    document.getElementById('popup').style.display = 'none';
                }, 3000); // Sembunyikan popup setelah 3 detik
            });
        }
        setInterval(showNextImage, 3000);

        function moveNext() {
            const books = document.querySelectorAll('.carousel .book');
            currentIndex = (currentIndex + 1) % books.length;
            books.forEach((book, index) => {
                book.style.transform = `translateX(-${currentIndex * 100}%)`;
            });
        }

        function movePrev() {
            const books = document.querySelectorAll('.carousel .book');
            currentIndex = (currentIndex - 1 + books.length) % books.length;
            books.forEach((book, index) => {
                book.style.transform = `translateX(-${currentIndex * 100}%)`;
            });
        }     

    </script>
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

// Fungsi untuk mulai gerakan otomatis
function startAutoMove() {
    setInterval(() => {
        moveCarousel('next'); // Pindah ke buku selanjutnya
    }, intervalTime);
}

// Mulai gerakan otomatis saat halaman dimuat
window.onload = startAutoMove;
</script>
</head>
<body>
    <header>
    <a href="index.php"> <!-- Ganti "index.php" dengan URL beranda utama Anda -->
        <img src="logo.png" alt="Library Logo" class="logo">
    </a>  
        <div class="header-search">
             <li class="search-form">
                <form action="search.php" method="get">
                    <input type="text" name="book_title" placeholder="Cari Judul/Pengarang/ISBN...">
                </form>
            </li>
        </div>
        <div class="header-links">
        <a href="loginn.php" class="log-button">Masuk</a>    
        <a href="register.php" class="reg-button">Daftar</a>
        </div>
    </header>
    <section>
    <div class="overlay-container">
    <h3 class="recommendation-title">Rekomendasi Buku</h3>
    <h4 class="recommendation-subtitle">Tingkatkan literasi membacamu hari ini!</h4>
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
        </div>
</section>  

<section class="main-topic-section">
    <h3 class="main-topic-title">TOPIK UTAMA</h3>
    <div class="news-container">
        <!-- News boxes here -->
        <div class="news-box">
            <img src="berita1.jpg" alt="News Image 1">
            <div class="news-content">
                <h4>ASN Disperpus Parepare Lolos Final Pustakawan Berprestasi Tingkat Nasional</h4>
                <div class="news-meta">HS - 22 MEI 2024</div>
                <p>DISPERPUSPAREPARE, NEWS - Pustakawan Dinas Perpustakaan Kota Parepare, Hery, S.I.P., M.I.P. lolos melaju ke babak final dalam ajang pemilihan Pustakawan Berprestasi Tingkat Nasional Tahun... </p>
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
        
    </section>
    <section class="ebook-container">
    <div class="ebook-content">
        <h3>Perpustakaan Berbasis Layanan Digital (PELITA)</h3>

    <div class="icon-container">
        <div class="ebook-icon ">
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
        <h3 style="color: black;   margin-top: 50px; margin-left: 60px; font-size: 30px; font-weight: 900;">Menemukan Masalah?</h3>
        <h3 style="color: #002C61; margin-top: -9px; margin-left: 60px; font-size: 30px; font-weight: 900;">Kami Siap Membantu</h3>
    </div>
    <div class="service-box">
        <h3 style=" margin-top: 10px;">Hubungi Kami</h3>
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
                    <li><a href="https://wa.me/628114128989">WhatsApp</a></li>
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




</form>
</body>
</html>
