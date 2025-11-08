<?php
session_start();

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include('db_con.php'); // Pastikan koneksi database sudah ada

// Ambil ID pengguna yang akan diedit
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Query untuk mendapatkan data pengguna berdasarkan ID
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika pengguna ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // Jika pengguna tidak ditemukan, redirect ke halaman manage user
        header("Location: manage_user.php");
        exit();
    }
} else {
    // Jika ID tidak ada, redirect ke halaman manage user
    header("Location: manage_user.php");
    exit();
}

// Proses pengeditan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat_tinggal = $_POST['alamat_tinggal'];
    $pekerjaan = $_POST['pekerjaan'];
    $tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'];
    $pendidikan_terakhir = $_POST['pendidikan_terakhir'];

    // Update data pengguna di database
    $sql_update = "UPDATE users SET fullname = ?, email = ?, role = ?, jenis_kelamin = ?, alamat_tinggal = ?, pekerjaan = ?, tempat_tanggal_lahir = ?, pendidikan_terakhir = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssssi", $name, $email, $role, $jenis_kelamin, $alamat_tinggal, $pekerjaan, $tempat_tanggal_lahir, $pendidikan_terakhir, $user_id);

    if ($stmt_update->execute()) {
        // Redirect ke halaman manage user setelah berhasil diupdate
        header("Location: manage_user.php");
        exit();
    } else {
        $error_message = "Failed to update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/edit_user.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_user.php">Manage Users</a></li>
                <li><a href="manage_books.php">Manage Books</a></li>
                <li><a href="site_settings.php">Site Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1>Edit User</h1>

            <!-- If there's an error message, display it -->
            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Form untuk mengedit user -->
            <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['fullname']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                </select><br>

                <label for="jenis_kelamin">Gender:</label>
                <select name="jenis_kelamin" required>
                    <option value="Male" <?php echo ($user['jenis_kelamin'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($user['jenis_kelamin'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select><br>

                <label for="alamat_tinggal">Address:</label>
                <input type="text" name="alamat_tinggal" value="<?php echo htmlspecialchars($user['alamat_tinggal']); ?>" required><br>

                <label for="pekerjaan">Occupation:</label>
                <input type="text" name="pekerjaan" value="<?php echo htmlspecialchars($user['pekerjaan']); ?>" required><br>

                <label for="tempat_tanggal_lahir">Date of Birth:</label>
                <input type="text" name="tempat_tanggal_lahir" value="<?php echo htmlspecialchars($user['tempat_tanggal_lahir']); ?>" required><br>

                <label for="pendidikan_terakhir">Last Education:</label>
                <input type="text" name="pendidikan_terakhir" value="<?php echo htmlspecialchars($user['pendidikan_terakhir']); ?>" required><br>

                <button type="submit">Update User</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Tutup koneksi database
$conn->close();
?>
