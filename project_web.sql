-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Okt 2025 pada 16.04
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
  `tahun_terbit` year(4) NOT NULL,
  `jumlah_eksemplar` int(11) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi` text DEFAULT NULL,
  `audience_category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`buku_id`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `jumlah_eksemplar`, `ISBN`, `kategori`, `genre`, `cover_image`, `created_at`, `deskripsi`, `audience_category`) VALUES
(3, 'Dudu Dulu', 'SYAHID MUHAMMAD', 'Gradien Mediatama', '2021', 3, '978-602-208-194-4', 'Novel', NULL, 'duduk-dulu.jpg', '2025-01-05 12:12:18', 'Buku Duduk Duku karya Syahid Muhammad ini berisi tulisan mengenai kehidupan kita sebagai manusia. Bukunya memuat tulisan demi tulisan tentang menghadapi permasalahan dan menjadikannya lebih dekat. Untuk menerima hidup dan segala perasaan yang muncul apa adanya, dari hal sedih dan senang.', '1-5'),
(4, 'Semesta Luruh', 'Wiji Thukul', 'Komunitas Bambu', '2013', 2, '978-602-9425-55-2', 'Novel', NULL, 'semesta_luruh.jpg', '2025-01-05 12:19:42', 'Buku Semesta Luruh Semesta Tumbuh ini mengajak kita untuk menyadari bahwa tidak selamanya semesta kita itu luruh. Pasti ada saatnya semesta kita bangkit lagi. Buku Semesta Luruh Semesta Tumbuh ini adalah tentang cara kita untuk bangkit kembali setelah jatuh tertimpa realitas.', NULL),
(5, 'Berkah Berkah Sepertiga Malam', 'A. Mustofa Bisri', 'LKiS (Lembaga Kajian Islam dan Sosial)', '2014', 5, '978-602-8340-10-6', 'Novel', NULL, 'sepertoga malam.jpg', '2025-01-05 12:26:50', 'Buku \"Berkah Berkah Sepertiga Malam\" adalah karya yang ditulis oleh A. Mustofa Bisri, seorang ulama dan sastrawan terkenal di Indonesia. Buku ini berisi refleksi spiritual dan renungan keagamaan.', NULL),
(6, 'Maling Kundang', 'Faulia Rahma', 'Tiga Serangkai', '2017', 2, '978-979-678-941-4', 'Novel', NULL, 'maling kundang.jpg', '2025-01-05 12:29:50', 'Buku ini mengadaptasi cerita legendaris Maling Kundang, yang mengisahkan seorang anak yang durhaka kepada ibunya dan akhirnya dikutuk menjadi batu. Cerita ini mengandung pesan moral tentang pentingnya berbakti kepada orang tua.\r\n\r\n\r\n\r\n\r\n', NULL),
(7, 'Introvert Abstrakim', ' Eka Kurniawan', 'Gramedia Pustaka Utama', '2016', 2, '978-602-03-0262-2', 'Novel', NULL, 'introvert.jpg', '2025-01-05 12:34:38', 'Buku ini menyelami dunia kepribadian introvert dengan cara yang penuh pemikiran dan refleksi pribadi. Dengan pendekatan yang unik, Eka Kurniawan memberikan pandangan yang lebih dalam mengenai kehidupan seorang introvert serta cara mereka menghadapi tantangan dalam masyarakat yang lebih banyak memberi ruang pada ekstrovert.', NULL),
(8, 'Sibungsu', 'Chrisna Putri', 'GagasMedia', '2017', 5, '978-602-03-2672-7', 'Komik', NULL, 'sibungsu.jpg', '2025-01-05 12:36:56', 'Sibungsu mengisahkan tentang kehidupan dan perjalanan batin karakter utama, dengan latar belakang budaya dan adat yang kental. Buku ini cocok bagi pembaca yang menyukai cerita dengan kedalaman emosional dan refleksi sosial.\r\n', NULL),
(9, 'Puteri Gunung Ledang', 'Tunku Halim', 'Kuala Lumpur, Penerbitan Ilham', '2012', 5, '978-983-3834-42-1', 'Novel', NULL, 'puteri gunung ledang.jpg', '2025-01-05 12:39:49', 'Puteri Gunung Ledang adalah kisah legendaris yang mengisahkan tentang Puteri Gunung Ledang, seorang wanita cantik yang dikenal karena kecantikannya dan kehidupannya yang penuh misteri. Cerita ini memadukan elemen cinta, pengorbanan, dan takdir, yang telah menjadi bagian dari warisan budaya Malaysia.', NULL),
(10, 'Login dan Gembira', 'Eko Nugroho', 'Penerbit Bentang Pustaka', '2018', 4, '978-602-291-485-5', 'Novel', NULL, 'berbhasa indonesia.jpg', '2025-01-05 12:43:23', '\"Login dan Gembira\" menyajikan kisah-kisah yang menggabungkan humor dengan kehidupan digital sehari-hari. Buku ini cocok bagi pembaca yang ingin merenung, namun dengan cara yang menyenangkan dan penuh canda.', NULL),
(11, 'Segala-galanya Ambyar', 'Alvi Syahrin', 'Elex Media Komputindo', '2020', 4, '978-623-00-2682-1', 'Novel', NULL, 'segalagalanya.jpg', '2025-01-05 14:20:49', 'Buku ini berbicara tentang kehidupan yang penuh dengan kesedihan dan kegagalan, diwarnai dengan berbagai perasaan galau dan serba salah yang dialami oleh tokoh-tokohnya. Dengan gaya penulisan yang khas, Alvi Syahrin membawa pembaca untuk lebih memahami pergulatan batin dan rasa kecewa dalam kehidupan sehari-hari.', NULL),
(12, 'Berpegang pada Kebenaran, Bertindak dengan Ketenangan', 'Soe Hok Gie', 'Mizan', '2004', 2, '978-979-433-015-4', 'Biografi', NULL, 'berpegang.jpg', '2025-01-05 14:24:01', 'Buku dengan judul Berpegang pada Kebenaran, Bertindak dengan Ketenangan adalah karya dari Soe Hok Gie, seorang intelektual dan aktivis Indonesia yang dikenal dengan pemikirannya yang tajam.', NULL),
(13, 'Seni Menguasai Lawan Bicara', 'Dale Carnegie', 'Gramedia Pustaka Utama', '2017', 6, '978-979-22-1003-7', 'Ensiklopedia', NULL, 'seni menguasai.jpg', '2025-01-05 14:28:34', 'Buku dengan judul Seni Menguasai Lawan Bicara adalah karya dari Dale Carnegie, seorang penulis dan pelatih terkenal dalam bidang pengembangan diri dan komunikasi. ', NULL),
(14, 'Senjata Hebat Para Introvert', 'Jen Granneman', 'Elex Media Komputindo', '2019', 6, '978-623-00-0979-4', 'Ensiklopedia', NULL, 'senjata hebat.jpg', '2025-01-05 14:31:11', 'Buku ini mengupas berbagai kekuatan yang dimiliki oleh introvert dan bagaimana mereka bisa memanfaatkannya sebagai \"senjata hebat\" dalam kehidupan sosial dan profesional. Penulis memberikan tips praktis untuk para introvert agar dapat lebih percaya diri, berbicara di depan umum, dan berinteraksi dengan orang lain, serta mengoptimalkan potensi diri mereka tanpa harus mengubah sifat alami mereka.\r\n\r\n\r\n\r\n\r\n', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `created_at`) VALUES
(2, 'laylirosalina@gmail.com', '$2y$10$BXvuHKgDh/dcLm1srtVTb.XRpV1ibYYwzreqpjR3kCLzm8mFR3HP2', '2025-01-03 12:57:25'),
(3, 'ummicantik29@gmail.com', '$2y$10$qUARGVtsPOrzoMzqOMjVvuOxHwJ1nFR5jRvfEtu2CfuwXdUNR82EG', '2025-10-27 03:52:18');

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
  `vehicle_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mobile_library_schedule`
--

INSERT INTO `mobile_library_schedule` (`id`, `reservation_id`, `scheduled_date`, `start_time`, `end_time`, `vehicle_id`, `created_at`) VALUES
(1, NULL, '2025-10-27', '2025-10-27 01:51:00', '2025-10-27 02:51:00', NULL, '2025-10-27 02:58:44'),
(2, NULL, '2025-10-27', '2025-10-27 01:51:00', '2025-10-27 02:51:00', NULL, '2025-10-27 03:24:36'),
(3, 6, '2025-10-27', '2025-10-27 11:01:00', '2025-10-27 12:01:00', NULL, '2025-10-27 03:24:36'),
(4, 6, '2025-10-27', '2025-10-27 11:01:00', '2025-10-27 12:01:00', NULL, '2025-10-27 06:51:55'),
(5, 7, '2025-10-27', '2025-10-27 15:50:00', '2025-10-27 16:50:00', NULL, '2025-10-27 06:51:55'),
(6, 8, '2025-10-28', '2025-10-28 21:36:00', '2025-10-28 22:36:00', NULL, '2025-10-28 14:30:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `optimization_logs`
--

CREATE TABLE `optimization_logs` (
  `id` int(11) NOT NULL,
  `optimized_at` datetime DEFAULT current_timestamp(),
  `before_optimization` text DEFAULT NULL,
  `after_optimization` text DEFAULT NULL,
  `total_distance` decimal(10,2) DEFAULT NULL,
  `reservations` text DEFAULT NULL,
  `status` enum('success','failure') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `request_letter` varchar(255) DEFAULT NULL,
  `gender` enum('Perempuan','Laki-laki') NOT NULL,
  `reservation_date` date NOT NULL,
  `status` enum('pending','confirmed') DEFAULT 'pending',
  `visit_time` time DEFAULT NULL,
  `visit_start` datetime DEFAULT NULL,
  `visit_end` datetime DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservations`
--

INSERT INTO `reservations` (`id`, `full_name`, `category`, `occupation`, `address`, `phone_number`, `request_letter`, `gender`, `reservation_date`, `status`, `visit_time`, `visit_start`, `visit_end`, `duration_minutes`) VALUES
(6, 'Ghina Odja', 'Institute Teknologi B.J Habibie', 'Festival ITH Keren', 'Jl. Patung Pemuda', '085123456789', 'surat_68fee5c32589d.pdf', 'Perempuan', '2025-10-27', 'confirmed', '11:01:00', NULL, NULL, 60),
(7, 'UMI KALSUM', 'PT. PLN UP3 Parepare', 'Hari Listrik Nasional', 'Kota Makassar, Jl Hertasning', '085240601703', 'surat_68ff165552e33.pdf', 'Perempuan', '2025-10-27', 'confirmed', '15:50:00', NULL, NULL, 60),
(8, 'Figra', 'PT Telkom Akses Area Parepare', 'Party ges', 'Kota Makassar, Jl Hertasning', '085123456789', 'surat_6900c6db700aa.pdf', 'Laki-laki', '2025-10-28', 'confirmed', '21:36:00', NULL, NULL, 60),
(9, 'Ghina', 'smk3 parepare', 'Hari Buku', 'Jl. Patung Pemuda', '082123456789', 'surat_6900d174ee4fb.pdf', 'Perempuan', '2025-10-28', '', '22:21:00', NULL, NULL, 60),
(10, 'TERSERAH', 'Universitas Hasanuddin', 'Hari Buku', 'Universitas Hasanuddin makassar', '08453718391', 'surat_6900da097263f.pdf', 'Laki-laki', '2025-10-28', 'confirmed', '22:58:00', NULL, NULL, 60);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `service_quality`, `book_availability`, `book_collection`, `created_at`, `service_quality_sentiment`, `book_availability_sentiment`, `book_collection_sentiment`) VALUES
(1, 'Pelayanan dari petugas perpustakaan keliling sangat memuaskan. Mereka ramah dan siap membantu pengunjung dengan senyuman. Setiap pertanyaan saya dijawab dengan baik, dan mereka memberikan rekomendasi buku yang sangat berguna.', 'Ketersediaan buku di perpustakaan keliling ini sangat baik. Mereka memiliki beragam genre dan judul, mulai dari buku anak-anak hingga novel dewasa. Saya selalu menemukan buku yang saya cari, dan koleksi mereka terus diperbarui.', 'Koleksi buku yang ditawarkan sangat bervariasi dan menarik. Terdapat banyak pilihan buku lokal dan internasional, serta buku-buku terbaru. Ini membuat pengalaman membaca menjadi lebih menyenangkan, dan saya merasa bahwa perpustakaan ini benar-benar peduli terhadap kebutuhan pembaca.\r\n\r\n', '2025-01-07 13:58:29', NULL, NULL, NULL),
(2, 'Meskipun ada petugas yang ramah, terkadang ada beberapa yang kurang responsif atau tidak bisa menjawab pertanyaan dengan baik. Saya pernah mengalami waktu tunggu yang cukup lama hanya untuk mendapatkan bantuan sederhana.', 'Meskipun banyak buku yang tersedia, terkadang ada beberapa judul yang saya cari tidak tersedia. Saya berharap perpustakaan keliling ini lebih sering memperbarui koleksinya dan memastikan judul-judul yang populer selalu ada.', 'Beberapa koleksi buku terlihat sudah tua dan tidak terawat. Beberapa buku memiliki halaman yang sobek atau kotor, yang mengurangi pengalaman membaca. Saya berharap perpustakaan lebih memperhatikan kondisi fisik buku-buku yang mereka miliki.', '2025-01-07 13:59:09', NULL, NULL, NULL),
(3, 'Pelayanan di perpustakaan keliling sangat luar biasa. Petugasnya selalu siap membantu dan memberikan informasi yang dibutuhkan. Mereka juga sering mengadakan sesi tanya jawab yang interaktif.', 'Perpustakaan ini memiliki koleksi buku yang sangat lengkap. Saya tidak pernah kesulitan menemukan buku yang saya inginkan, bahkan buku-buku terbaru sekalipun.', 'Koleksi buku mereka mencakup berbagai genre, dari fiksi hingga non-fiksi. Saya sangat menyukai koleksi buku anak-anak yang beragam dan menarik.', '2025-01-07 13:59:57', NULL, NULL, NULL),
(4, 'Meskipun ada beberapa petugas yang ramah, ada kalanya saya merasa kurang diperhatikan. Terkadang, waktu tunggu untuk mendapatkan bantuan cukup lama.', 'Beberapa buku yang saya cari tidak tersedia. Ada saat-saat ketika koleksi mereka tampak terbatas pada genre tertentu.', 'Koleksi buku lama yang kurang diperbarui membuat saya merasa kurang puas. Beberapa buku tampaknya sudah usang dan tidak relevan lagi.', '2025-01-07 14:00:31', NULL, NULL, NULL),
(5, 'Petugas perpustakaan sangat profesional dan mengerti kebutuhan pengunjung. Mereka selalu memberikan rekomendasi buku yang sesuai dengan minat saya.', 'Ketersediaan buku sangat memuaskan. Saya senang mereka selalu memperbarui koleksi dengan buku-buku terbaru, sehingga saya bisa menemukan bacaan yang fresh.', 'Koleksi buku sangat bervariasi dan mencakup banyak kategori. Pengalaman membaca saya menjadi lebih kaya karena banyaknya pilihan yang tersedia.', '2025-01-07 14:01:19', NULL, NULL, NULL),
(6, 'Ada kalanya saya merasa petugas tidak cukup memberikan informasi yang jelas. Saya mengalami kesulitan dalam mencari buku tertentu karena kurangnya panduan yang diberikan.', 'Saya sering menemukan bahwa buku yang saya cari tidak tersedia. Ini sangat mengecewakan, terutama ketika saya sudah sangat ingin membacanya.', 'Koleksi buku tidak selalu diperbarui dengan judul-judul terbaru. Saya berharap bisa melihat lebih banyak variasi dalam koleksi mereka.', '2025-01-07 14:02:03', NULL, NULL, NULL),
(7, 'baik', 'baik', 'baik', '2025-09-12 12:21:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nik` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `tempat_tanggal_lahir` date NOT NULL,
  `alamat_tinggal` text NOT NULL,
  `pendidikan_terakhir` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `usia` int(2) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin','','') NOT NULL,
  `profile_picture` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nik`, `fullname`, `tempat_tanggal_lahir`, `alamat_tinggal`, `pendidikan_terakhir`, `jenis_kelamin`, `pekerjaan`, `usia`, `email`, `password`, `created_at`, `role`, `profile_picture`) VALUES
(4, '7372026003030111', 'Layli Rosalina', '2001-06-12', 'Jln.Jendral ahmad yani', 'SMK NEGRI 3 PAREPARE', 'Laki-laki', 'Mahasiswi', 21, 'laylirosalina@gmail.com', '$2y$10$BXvuHKgDh/dcLm1srtVTb.XRpV1ibYYwzreqpjR3kCLzm8mFR3HP2', '2025-01-03 12:57:25', 'admin', ''),
(5, '7315046909030004', 'Ummi Cantik', '2003-09-29', 'JL. Kebun Sayur', 'SMK NEGRI 3 PAREPARE', 'Perempuan', 'Mahasiswi', 22, 'ummicantik29@gmail.com', '$2y$10$qUARGVtsPOrzoMzqOMjVvuOxHwJ1nFR5jRvfEtu2CfuwXdUNR82EG', '2025-10-27 03:52:18', 'user', 'uploads/profile_68feecb6dd4d4.JPG');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`buku_id`),
  ADD UNIQUE KEY `ISBN` (`ISBN`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `mobile_library_schedule`
--
ALTER TABLE `mobile_library_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_schedule_reservation` (`reservation_id`);

--
-- Indeks untuk tabel `optimization_logs`
--
ALTER TABLE `optimization_logs`
  ADD PRIMARY KEY (`id`);

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
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `buku_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mobile_library_schedule`
--
ALTER TABLE `mobile_library_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `optimization_logs`
--
ALTER TABLE `optimization_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mobile_library_schedule`
--
ALTER TABLE `mobile_library_schedule`
  ADD CONSTRAINT `fk_schedule_reservation` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
