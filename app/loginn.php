<?php
session_start();
include('db_con.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Pastikan koneksi berhasil
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk memeriksa apakah email ada di database
    $stmt = $conn->prepare("SELECT id, email, password, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email); // Bind parameter email
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email, $hashed_password, $role);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['userid'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['role'] = $role; // Menyimpan role di session

            // Pengalihan berdasarkan peran
            if ($role == 'admin') {
                header("Location: admin_dashboard.php"); // Arahkan ke halaman admin
            } else {
                header("Location: user_dashboard.php"); // Arahkan ke halaman user biasa
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Kawan Perpus</title>
    <link rel="stylesheet" href="css/loginn.css"/>
</head>
<body>
    <div class="wrapper">
        <form method="POST" action="loginn.php<?php if (isset($_GET['redirect'])) { echo '?redirect=' . urlencode($_GET['redirect']); } ?>">
            <!-- Tambahkan logo di sini -->
            <a href="index.php" >
                <img src="css/logo.png" alt="Logo Perpustakaan"  class="logo">
            </a>
            <h1 style="color: #fff; font-size: 20px; margin-top: 20px; margin-bottom: 15px;">LOGIN KAWAN PERPUS</h1>
            <?php
            // Menampilkan pesan error jika ada
            if (isset($error_message)) {
                echo "<div style='color: red; text-align: center; margin-bottom: 10px;'>$error_message</div>";
            }
            ?>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="input-box">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember-forgot">
                <div>
                    <label>
                        <input type="checkbox" name="remember"> Ingat Saya
                    </label>
                </div>
                <div>
                    <a href="#">Lupa Kata Sandi?</a>
                </div>
            </div>
            <button type="submit" class="btn">Masuk</button>
            <p style="color: #fff; font-size: 10px; text-align: center;">Belum memiliki akun? <a style="color: #fff;" href="register.php">Daftar disini</a></p>
        </form>
    </div>
</body>
</html>