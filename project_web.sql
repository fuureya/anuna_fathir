-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Nov 2025 pada 04.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_web`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `buku_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` varchar(4) NOT NULL,
  `jumlah_eksemplar` int(11) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi` text DEFAULT NULL,
  `audience_category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`buku_id`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `jumlah_eksemplar`, `ISBN`, `kategori`, `genre`, `cover_image`, `created_at`, `deskripsi`, `audience_category`) VALUES
(1, 'Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Bloomsbury', '1997', 10, '9780747532699', 'Novel', 'Fiction', 'https://m.media-amazon.com/images/I/81q77Q39nEL._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Harry Potter yang berusia sebelas tahun menemukan bahwa ia adalah seorang penyihir dan memulai petualangannya di Hogwarts School of Witchcraft and Wizardry.', 'general'),
(2, 'To Kill a Mockingbird', 'Harper Lee', 'J.B. Lippincott & Co.', '1960', 8, '9780061120084', 'Novel', 'Fiction', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqNqiNVbPQXOqQbSHZXnoXuEYCJfMxtCsDXQ&s', '2025-11-11 18:55:09', 'Novel klasik tentang rasisme dan ketidakadilan di Amerika Selatan tahun 1930-an, diceritakan melalui mata seorang anak kecil.', 'general'),
(3, '1984', 'George Orwell', 'Secker & Warburg', '1949', 12, '9780451524935', 'Novel', 'Fiction', 'https://m.media-amazon.com/images/I/71wANojhEKL._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Novel dystopian tentang masyarakat totalitarian di bawah pengawasan konstan oleh \"Big Brother\".', 'general'),
(4, 'Sejarah Indonesia Modern', 'Ricklefs, M.C.', 'Gadjah Mada University Press', '2008', 15, '9789794202418', 'Biografi', 'Non-Fiction', 'https://www.lib.bwi.go.id/wp-content/uploads/2021/01/335-scaled.jpg', '2025-11-11 18:55:09', 'Buku komprehensif tentang sejarah Indonesia dari masa kolonial hingga era reformasi.', 'general'),
(5, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 20, '9789793062792', 'Novel', 'Fiction', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1489732961i/1362193.jpg', '2025-11-11 18:55:09', 'Novel inspiratif tentang kehidupan dan perjuangan anak-anak di Belitung untuk mendapatkan pendidikan.', 'general'),
(6, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', '1980', 10, '9789799731234', 'Novel', 'Fiction', 'https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,f_auto,q_auto:best,w_640/v1564403166/fkxrdlvv9feozrkznbp5.jpg', '2025-11-11 18:55:09', 'Novel pertama dari Tetralogi Buru yang mengisahkan kehidupan di masa kolonial Belanda.', 'general'),
(7, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper', '2014', 18, '9780062316097', 'Ensiklopedia', 'Non-Fiction', 'https://m.media-amazon.com/images/I/716E6dQ4BXL._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Sejarah singkat umat manusia dari evolusi Homo sapiens hingga era modern.', 'general'),
(8, 'Ensiklopedia Biologi', 'Tim Penulis', 'Erlangga', '2018', 7, '9786024341234', 'Ensiklopedia', 'Education', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzjDOGpdm-fGPY8npXTAgb72hlgCWByaCtiA&s', '2025-11-11 18:55:09', 'Ensiklopedia lengkap tentang berbagai aspek biologi untuk pelajar dan umum.', 'general'),
(9, 'The Little Prince', 'Antoine de Saint-Exupéry', 'Reynal & Hitchcock', '1943', 14, '9780156012195', 'Novel', 'Fiction', 'https://m.media-amazon.com/images/I/71NsO7LVa3L._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Cerita klasik tentang seorang pangeran kecil yang melakukan perjalanan dari planet ke planet.', 'children'),
(10, 'Matematika untuk SMA', 'Tim Guru Indonesia', 'Gramedia', '2020', 25, '9786020638234', 'Komik', 'Education', 'https://cdn.gramedia.com/uploads/products/tmkkx5snm0.jpg', '2025-11-11 18:55:09', 'Buku pelajaran matematika lengkap untuk siswa SMA kelas 10-12.', 'students'),
(11, 'Ronggeng Dukuh Paruk', 'Ahmad Tohari', 'Gramedia Pustaka Utama', '1982', 9, '9789792202076', 'Novel', 'Fiction', 'https://gpu.id/data-gpu/images/img-book/94834/625173001.jpg', '2025-11-11 18:55:09', 'Novel tentang kehidupan ronggeng di sebuah dukuh terpencil di Jawa Tengah.', 'general'),
(12, 'Sejarah Peradaban Islam', 'Badri Yatim', 'Raja Grafindo Persada', '2006', 11, '9789797693879', 'Biografi', 'Non-Fiction', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUo6tEpC67th2Q-Zlhg5KfrDVQtx817xHLLg&s', '2025-11-11 18:55:09', 'Buku tentang perkembangan peradaban Islam dari masa awal hingga modern.', 'general'),
(13, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia Pustaka Utama', '2002', 12, '9789792218039', 'Novel', 'Fiction', 'https://cdn.gramedia.com/uploads/items/9786020366517_Cantik-Itu-Luka-Hard-Cover---Limited-Edition.jpg', '2025-11-11 18:55:09', 'Novel magis realis tentang Dewi Ayu, pelacur tercantik di kota kecil yang bangkit dari kubur setelah 21 tahun.', 'general'),
(14, 'Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', 'Republika', '2004', 15, '9789793062808', 'Novel', 'Fiction', 'https://www.gramedia.com/blog/content/images/2025/04/ayatayatcinta.jpg', '2025-11-11 18:55:09', 'Novel romantis islami tentang Fahri, mahasiswa Indonesia di Mesir yang terjerat kisah cinta.', 'general'),
(15, 'Sang Pemimpi', 'Andrea Hirata', 'Bentang Pustaka', '2006', 14, '9789793062815', 'Novel', 'Fiction', 'https://upload.wikimedia.org/wikipedia/id/8/89/Sang_Pemimpi_sampul.jpg', '2025-11-11 18:55:09', 'Sekuel Laskar Pelangi. Kisah perjuangan Ikal dan Arai mengejar mimpi hingga ke Eropa.', 'general'),
(16, 'Tenggelamnya Kapal Van Der Wijck', 'Hamka', 'Bulan Bintang', '1973', 10, '9789795110934', 'Novel', 'Fiction', 'https://cdn.gramedia.com/uploads/items/img067.jpg', '2025-11-11 18:55:09', 'Kisah cinta tragis Zainuddin dan Hayati yang terhalang perbedaan status sosial.', 'general'),
(17, 'Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', '2009', 18, '9789797806934', 'Novel', 'Fiction', 'https://static.mizanstore.com/d/img/book/cover/covBT-543.jpg', '2025-11-11 18:55:09', 'Kisah cinta remaja tentang Kugy dan Keenan yang saling mengejar mimpi sambil mencari cinta sejati.', 'general'),
(18, 'Filosofi Teras', 'Henry Manampiring', 'Kompas Gramedia', '2019', 22, '9786024246945', 'Ensiklopedia', 'Non-Fiction', 'https://imgv2-2-f.scribdassets.com/img/document/407689652/original/ec1d808baa/1?v=1', '2025-11-11 18:55:09', 'Filsafat Stoicisme untuk mengatasi anxiety dan stress di era modern.', 'general'),
(19, 'Atomic Habits', 'James Clear', 'Gramedia Pustaka Utama', '2019', 25, '9786020633176', 'Ensiklopedia', 'Non-Fiction', 'https://cdn.gramedia.com/uploads/items/9786020633176_.Atomic_Habit.jpg', '2025-11-11 18:55:09', 'Panduan praktis membangun kebiasaan baik dan menghilangkan kebiasaan buruk.', 'general'),
(20, 'Mindset: Psikologi Sukses', 'Carol S. Dweck', 'Serambi Ilmu Semesta', '2017', 16, '9786024522667', 'Ensiklopedia', 'Non-Fiction', 'https://m.media-amazon.com/images/I/71h937MExWL._AC_UF350,350_QL50_.jpg', '2025-11-11 18:55:09', 'Penelitian tentang growth mindset vs fixed mindset dalam mencapai kesuksesan.', 'general'),
(21, 'The 7 Habits of Highly Effective People', 'Stephen R. Covey', 'Free Press', '1989', 19, '9780743269513', 'Ensiklopedia', 'Non-Fiction', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSa-VhVmPDuaRa3fnFNgL4JA8gE0ry03vTYng&s', '2025-11-11 18:55:09', '7 kebiasaan orang yang sangat efektif untuk kesuksesan personal dan profesional.', 'general'),
(22, 'Kancil dan Buaya', 'Tim Penulis Bhuana', 'Bhuana Ilmu Populer', '2018', 30, '9786024525187', 'Komik', 'Fiction', 'https://bukukita.com/babacms/displaybuku/97543_f.jpg', '2025-11-11 18:55:09', 'Cerita rakyat nusantara tentang kancil yang cerdik mengelabui buaya.', 'children'),
(23, 'Si Pitung: Pahlawan Betawi', 'Tim Penulis Gramedia', 'Gramedia', '2019', 20, '9786020633145', 'Komik', 'Fiction', 'https://dpk.kepriprov.go.id/resources/cover/2018-11-28_SI-PITUNG-PAHLAWAN-BETAWI-SUPRIYANTININGTYAS-WHENI-KUSUMANINGSIH_022428.jpg', '2025-11-11 18:55:09', 'Kisah heroik Si Pitung, jagoan Betawi yang melawan ketidakadilan.', 'children'),
(24, 'Petualangan Sherina', 'Jujur Prananto', 'Gramedia Pustaka Utama', '2000', 15, '9789796867264', 'Novel', 'Fiction', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3VHUIOer5vIkVv7wcxzdeUtzzGnTZm_um3g&s', '2025-11-11 18:55:09', 'Petualangan seru Sherina dan Sadam di perkebunan teh melawan penjahat.', 'children'),
(25, 'Keluarga Cemara', 'Arswendo Atmowiloto', 'Gramedia Pustaka Utama', '2018', 18, '9786020630359', 'Novel', 'Fiction', 'https://gpu.id/data-gpu/images/img-book/94147/623133001.jpg', '2025-11-11 18:55:09', 'Cerita keluarga yang mengajarkan nilai kebersamaan dan kebahagiaan sederhana.', 'children'),
(26, 'Al-Quran dan Terjemahan', 'Kementerian Agama RI', 'Sygma', '2020', 50, '9786232160545', 'Biografi', 'Religion', 'https://elibrary.bsi.ac.id/assets/images/buku/213178.jpeg', '2025-11-11 18:55:09', 'Al-Quran lengkap 30 juz dengan terjemahan bahasa Indonesia dan tajwid.', 'general'),
(27, 'Tafsir Ibnu Katsir', 'Ibnu Katsir', 'Pustaka Imam Syafi\'i', '2016', 12, '9786021613191', 'Biografi', 'Religion', 'https://bintangpusnas.perpusnas.go.id/api25/public/api/get-image/00b9009c8ff2eddb765fd07f1a412ecb.webp', '2025-11-11 18:55:09', 'Tafsir Al-Quran lengkap dari Imam Ibnu Katsir yang terkenal.', 'general'),
(28, 'Kitab Tauhid', 'Muhammad bin Abdul Wahhab', 'Darul Haq', '2017', 20, '9786025734106', 'Biografi', 'Religion', 'https://dpk.kepriprov.go.id/resources/cover/2023-12-13_KITAB-TAUHID-SYAIKH-MUHAMMAD-BIN-ABDUL-WAHAB-AHLI-BAHASA-M-YUSUF-HARUN_104840.jpg', '2025-11-11 18:55:09', 'Kitab klasik tentang dasar-dasar tauhid dan keimanan dalam Islam.', 'general'),
(29, 'Si Juki: Kumpulan Strip', 'Faza Meonk', 'Bukune', '2019', 25, '9786237222026', 'Komik', 'Fiction', 'https://bukukita.com/babacms/displaybuku/79905_f.jpg', '2025-11-11 18:55:09', 'Komik strip menghibur dengan sindiran sosial kehidupan anak muda Indonesia.', 'general'),
(30, 'Naruto Vol. 1', 'Masashi Kishimoto', 'Elex Media Komputindo', '2008', 28, '9789792248661', 'Komik', 'Fiction', 'https://m.media-amazon.com/images/I/91RpwagB7uL._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Manga populer tentang Uzumaki Naruto, ninja muda bercita-cita jadi Hokage.', 'children'),
(31, 'One Piece Vol. 1', 'Eiichiro Oda', 'Elex Media Komputindo', '2007', 30, '9789792247954', 'Komik', 'Fiction', 'https://cdn.gramedia.com/uploads/picture_meta/2023/6/27/dtk4llf7bhxhees7rn8nkp.jpg', '2025-11-11 18:55:09', 'Petualangan Monkey D. Luffy mencari harta karun One Piece. Manga terlaris dunia.', 'children'),
(32, 'Doraemon Vol. 1', 'Fujiko F. Fujio', 'Elex Media Komputindo', '2005', 35, '9789792232714', 'Komik', 'Fiction', 'https://images-cdn.ubuy.co.in/65b20cbf57068467bd2ec257-doraemon-vol-1-kindle-edition.jpg', '2025-11-11 18:55:09', 'Komik klasik tentang robot kucing dari masa depan yang membantu Nobita.', 'children'),
(33, 'Brief Answers to the Big Questions', 'Stephen Hawking', 'Bentang Pustaka', '2019', 13, '9786024526726', 'Ensiklopedia', 'Education', 'https://m.media-amazon.com/images/I/91Gz1OrE9-L._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Karya terakhir Stephen Hawking menjawab pertanyaan besar tentang alam semesta.', 'general'),
(34, 'Homo Deus: Masa Depan Umat Manusia', 'Yuval Noah Harari', 'Pustaka Alvabet', '2018', 15, '9786024524616', 'Ensiklopedia', 'Non-Fiction', 'https://tokoalvabet.com/84-large_default/homo-deus-masa-depan-umat-manusia.jpg', '2025-11-11 18:55:09', 'Sekuel Sapiens tentang masa depan manusia di era AI dan bioengineering.', 'general'),
(35, 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 'Gramedia Pustaka Utama', '2017', 28, '9786020319889', 'Ensiklopedia', 'Non-Fiction', 'https://gpu.id/data-gpu/images/img-book/94906/625203007.jpg', '2025-11-11 18:55:09', 'Buku klasik literasi keuangan tentang mindset orang kaya vs orang miskin.', 'general'),
(36, 'The Lean Startup', 'Eric Ries', 'Bentang Pustaka', '2018', 17, '9786024526108', 'Ensiklopedia', 'Non-Fiction', 'https://m.media-amazon.com/images/I/71sxTeZIi6L._AC_UF1000,1000_QL80_.jpg', '2025-11-11 18:55:09', 'Metodologi membangun startup dengan build-measure-learn cycle.', 'general'),
(37, 'Fisika untuk SMA Kelas X', 'Marthen Kanginan', 'Erlangga', '2020', 40, '9786024341567', 'Komik', 'Education', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrUC0LfxgSs0Em5ZXeu9qAUFwr0DlB57dqmg&s', '2025-11-11 18:55:09', 'Buku pelajaran fisika lengkap untuk siswa SMA kelas 10 sesuai kurikulum.', 'students'),
(38, 'Kimia untuk SMA Kelas X', 'Michael Purba', 'Erlangga', '2020', 40, '9786024341574', 'Komik', 'Education', 'https://www.getpress.co.id/backend/upload/buku/KIMIA%20KELAS%20X%20KURIKULUM%20MERDEKA.jpg-1700019435.jpg', '2025-11-11 18:55:09', 'Buku pelajaran kimia komprehensif untuk siswa SMA kelas 10.', 'students'),
(39, 'Bahasa Indonesia untuk SMA', 'Tim Edukatif', 'Erlangga', '2021', 45, '9786024341581', 'Komik', 'Education', 'https://a.cdn-myedisi.com/book/cover/bse-a_5c3c172c7d809104757019.jpg', '2025-11-11 18:55:09', 'Buku pelajaran Bahasa Indonesia lengkap dengan latihan soal dan pembahasan.', 'students');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bus_tracking`
--

CREATE TABLE `bus_tracking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tracking_date` date NOT NULL,
  `current_reservation_id` int(11) DEFAULT NULL,
  `bus_status` enum('idle','on_the_way','arrived','serving','finished') NOT NULL DEFAULT 'idle',
  `current_latitude` decimal(10,7) DEFAULT NULL,
  `current_longitude` decimal(10,7) DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bus_tracking`
--

INSERT INTO `bus_tracking` (`id`, `tracking_date`, `current_reservation_id`, `bus_status`, `current_latitude`, `current_longitude`, `status_updated_at`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2025-11-11', 2, 'idle', -4.0097520, 119.6224600, '2025-11-11 14:53:31', 1, '2025-11-11 14:51:50', '2025-11-11 14:53:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_11_000001_create_books_table', 1),
(5, '2025_11_11_000002_create_login_table', 1),
(6, '2025_11_11_000003_create_mobile_library_schedule_table', 1),
(7, '2025_11_11_000004_create_optimization_logs_table', 1),
(8, '2025_11_11_000005_create_reservations_table', 1),
(9, '2025_11_11_000006_create_reviews_table', 1),
(10, '2025_11_11_000007_add_fk_mobile_schedule_reservation', 1),
(11, '2025_11_11_000008_fix_mobile_reservation_signed', 1),
(12, '2025_11_11_000009_add_updated_at_to_users_table', 1),
(13, '2025_11_11_000010_add_geo_and_audience_to_reservations', 1),
(14, '2025_11_11_112412_add_email_to_reservations_table', 1),
(15, '2025_11_11_124234_add_rejection_reason_to_reservations_table', 1),
(16, '2025_11_11_124247_add_rejection_reason_to_reservations_table', 1),
(17, '2025_11_11_141255_add_end_time_to_reservations_table', 1),
(18, '2025_11_11_222802_create_bus_tracking_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobile_library_schedule`
--

CREATE TABLE `mobile_library_schedule` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `vehicle_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mobile_library_schedule`
--

INSERT INTO `mobile_library_schedule` (`id`, `reservation_id`, `scheduled_date`, `start_time`, `end_time`, `vehicle_id`, `created_at`) VALUES
(11, 1, '2025-11-11', '2025-11-11 09:00:00', '2025-11-11 11:00:00', NULL, '2025-11-11 18:49:03'),
(12, 2, '2025-11-11', '2025-11-11 11:30:00', '2025-11-11 13:30:00', NULL, '2025-11-11 18:49:03'),
(13, 3, '2025-11-11', '2025-11-11 14:00:00', '2025-11-11 16:00:00', NULL, '2025-11-11 18:49:03'),
(14, 4, '2025-11-11', '2025-11-11 16:00:00', '2025-11-11 18:00:00', NULL, '2025-11-11 18:49:03'),
(15, 5, '2025-11-25', '2025-11-25 12:08:00', '2025-11-25 14:08:00', NULL, '2025-11-11 18:49:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `optimization_logs`
--

CREATE TABLE `optimization_logs` (
  `id` int(11) NOT NULL,
  `optimized_at` datetime NOT NULL DEFAULT current_timestamp(),
  `before_optimization` text DEFAULT NULL,
  `after_optimization` text DEFAULT NULL,
  `total_distance` decimal(10,2) DEFAULT NULL,
  `reservations` text DEFAULT NULL,
  `status` enum('success','failure') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) NOT NULL,
  `audience_category` varchar(50) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `request_letter` varchar(255) DEFAULT NULL,
  `gender` enum('Perempuan','Laki-laki') NOT NULL,
  `reservation_date` date NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `visit_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `visit_start` datetime DEFAULT NULL,
  `visit_end` datetime DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reservations`
--

INSERT INTO `reservations` (`id`, `full_name`, `email`, `category`, `occupation`, `audience_category`, `address`, `phone_number`, `request_letter`, `gender`, `reservation_date`, `latitude`, `longitude`, `status`, `rejection_reason`, `visit_time`, `end_time`, `visit_start`, `visit_end`, `duration_minutes`) VALUES
(1, 'Contact Person 1', 'contact1@test.com', 'Pendidikan', 'SDN 1 Parepare', 'Umum', 'Jl. Pendidikan No. 10, Parepare', '081234567810', NULL, 'Laki-laki', '2025-11-11', -4.0102000, 119.6215000, 'confirmed', NULL, '09:00:00', '11:00:00', NULL, NULL, 120),
(2, 'Contact Person 2', 'contact2@test.com', 'Pendidikan', 'SMP Negeri 3 Parepare', 'Umum', 'Jl. Veteran No. 25, Parepare', '081234567820', NULL, 'Laki-laki', '2025-11-11', -4.0089000, 119.6245000, 'confirmed', NULL, '11:30:00', '13:30:00', NULL, NULL, 120),
(3, 'Contact Person 3', 'contact3@test.com', 'Pendidikan', 'Kantor Kelurahan Bukit Harapan', 'Umum', 'Jl. Harapan Indah No. 5, Parepare', '081234567830', NULL, 'Laki-laki', '2025-11-11', -4.0120000, 119.6200000, 'confirmed', NULL, '14:00:00', '16:00:00', NULL, NULL, 120),
(4, 'Contact Person 4', 'contact4@test.com', 'Pendidikan', 'Masjid Al-Ikhlas', 'Umum', 'Jl. Mesjid Raya No. 12, Parepare', '081234567840', NULL, 'Laki-laki', '2025-11-11', -4.0075000, 119.6260000, 'confirmed', NULL, '16:00:00', '18:00:00', NULL, NULL, 120),
(5, 'moh.ilham fariqulzaman', 'zamanilham57@gmail.com', 'event', 'membaca', NULL, 'jl tupai', '081244586514', 'surat_wGEn7TM3l3fnVIB3.pdf', 'Laki-laki', '2025-11-25', NULL, NULL, 'confirmed', NULL, '12:08:00', '14:08:00', NULL, NULL, 120);

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `service_quality` text NOT NULL,
  `book_availability` text NOT NULL,
  `book_collection` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `service_quality_sentiment` enum('Positif','Negatif','Netral') DEFAULT NULL,
  `book_availability_sentiment` enum('Positif','Negatif','Netral') DEFAULT NULL,
  `book_collection_sentiment` enum('Positif','Negatif','Netral') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Ce0sD46tbpiyLlAqgYOcaFRBm9Te0P6i6R73iYsb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVExyV2lYTkphOHdjRlVRUzZTR0toT0MxcHBjQ0NnSGRCUjhFVGdkMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib29rcy8xNyI7fX0=', 1762889439);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `tempat_tanggal_lahir` date NOT NULL,
  `alamat_tinggal` text NOT NULL,
  `pendidikan_terakhir` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `usia` tinyint(3) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `profile_picture` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nik`, `fullname`, `tempat_tanggal_lahir`, `alamat_tinggal`, `pendidikan_terakhir`, `jenis_kelamin`, `pekerjaan`, `usia`, `email`, `password`, `created_at`, `updated_at`, `role`, `profile_picture`) VALUES
(1, '1234567890123456', 'Admin Pusling', '1990-01-01', 'Jl. Perpustakaan No. 1, Parepare', 'S1', 'Laki-laki', 'Admin Perpustakaan', 34, 'admin@pusling.com', '$2y$12$CR8jbf9a2FG99yeApD78jOM1XaqniuXIeNAXlDYV08xaCkWFGirJu', '2025-11-11 14:39:37', '2025-11-11 14:39:37', 'admin', NULL),
(2, '1234567890123457', 'User Test', '1995-05-05', 'Jl. Test No. 2, Parepare', 'SMA', 'Perempuan', 'Guru', 29, 'user@test.com', '$2y$12$kVWcCIoW1Sv5PLI.IBi63eBa/IFDS35E85mNjRzkK5.rVyyEsQPGu', '2025-11-11 14:39:37', '2025-11-11 14:39:37', 'user', NULL),
(4, 'USER1762884463', 'moh.ilham fariqulzaman', '2005-11-12', 'Belum diisi', 'Belum diisi', 'Laki-laki', 'Belum diisi', 20, 'zamanilham57@gmail.com', '$2y$12$WM4O2qHv8ogV8p2X.wveLO2ppA6Y2l.5EjiXGQLH8nalcxTSkzDL6', '2025-11-11 18:07:44', '2025-11-11 18:07:44', 'user', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`buku_id`),
  ADD UNIQUE KEY `books_isbn_unique` (`ISBN`);

--
-- Indeks untuk tabel `bus_tracking`
--
ALTER TABLE `bus_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_email_unique` (`email`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mobile_library_schedule`
--
ALTER TABLE `mobile_library_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile_library_schedule_reservation_id_index` (`reservation_id`);

--
-- Indeks untuk tabel `optimization_logs`
--
ALTER TABLE `optimization_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `buku_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `bus_tracking`
--
ALTER TABLE `bus_tracking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `mobile_library_schedule`
--
ALTER TABLE `mobile_library_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `optimization_logs`
--
ALTER TABLE `optimization_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_email_foreign` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mobile_library_schedule`
--
ALTER TABLE `mobile_library_schedule`
  ADD CONSTRAINT `mobile_library_schedule_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
