<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "project_web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nik = $_POST['nik'];
    $fullname = $_POST['fullname'];
    $tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'];
    $alamat_tinggal = $_POST['alamat_tinggal'];
    $pendidikan_terakhir = $_POST['pendidikan_terakhir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $pekerjaan = $_POST['pekerjaan'];
    $usia = $_POST['usia'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "Password dan Konfirmasi Password tidak cocok.";
        exit();
    }

    // Hash password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT); 

    // Prepare SQL statement untuk memasukkan data pengguna ke tabel users
    $stmt = $conn->prepare("INSERT INTO users (nik, fullname, tempat_tanggal_lahir, alamat_tinggal, pendidikan_terakhir, jenis_kelamin, pekerjaan, usia, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssiss", $nik, $fullname, $tempat_tanggal_lahir, $alamat_tinggal, $pendidikan_terakhir, $jenis_kelamin, $pekerjaan, $usia, $email, $password_hashed);

    if ($stmt->execute()) {
        // Setelah data di users berhasil dimasukkan, masukkan data login ke tabel login
        $login_stmt = $conn->prepare("INSERT INTO login (email, password) VALUES (?, ?)");
        $login_stmt->bind_param("ss", $email, $password_hashed);
        $login_stmt->execute();
        
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $fullname;
        $_SESSION['role'] = 'user'; // Role bisa disesuaikan

        header('Location: user_dashboard.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/regiss.css">
</head>
<body>
    <div class="wrapper">
        <form method="POST" action="register.php">
        <a href="index.php" >
                <img src="css/logo.png" alt="Logo Perpustakaan"  class="logo">
            </a>
            <h1>PENDAFTARAN KAWAN PERPUS</h1>

            <div class="input-row">
                <div class="input-box">
                    <label for="nik">NIK (Untuk WNI) atau KITAS (Untuk WNA)*</label>
                    <input type="text" id="nik" name="nik" required>
                </div>
                <div class="input-box">
                    <label for="fullname">Nama Lengkap*</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label for="tempat_tanggal_lahir">Tempat dan Tanggal Lahir*</label>
                    <input type="date" id="tempat_tanggal_lahir" name="tempat_tanggal_lahir" required>
                </div>
                <div class="input-box">
                    <label for="alamat_tinggal">Alamat Tinggal*</label>
                    <textarea id="alamat_tinggal" name="alamat_tinggal" required></textarea>
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label for="pendidikan_terakhir">Pendidikan Terakhir*</label>
                    <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                </div>
                <div class="input-box">
                    <label for="jenis_kelamin">Jenis Kelamin*</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label for="pekerjaan">Pekerjaan*</label>
                    <input type="text" id="pekerjaan" name="pekerjaan" required>
                </div>
                <div class="input-box">
                    <label for="usia">Usia*</label>
                    <input type="number" id="usia" name="usia" required>
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-box">
                    <label for="password">Kata Sandi*</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label for="confirm_password">Konfirmasi Kata Sandi*</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>

            <div class="custom-checkbox">
                <input type="checkbox" id="privacyPolicy" name="privacyPolicy" required>
                <label for="privacyPolicy">Saya menyatakan telah membaca dan menyetujui terkait Kebijakan Privasi</label>
            </div>

            <button type="submit" class="btn">Daftar</button>

            <div class="login-link">
                <p>Sudah memiliki akun? <a href="login.php">Masuk</a></p>
            </div>
        </form>
    </div>
</body>
</html>
