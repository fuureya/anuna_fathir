<?php
session_start();
include 'db_con.php';  // Pastikan Anda sudah melakukan koneksi ke database

// Periksa apakah userid ada dalam session
if (!isset($_SESSION['userid'])) {
    header("Location: loginn.php");
    exit();
}

// Ambil data pengguna dari database
$userId = $_SESSION['userid'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Periksa apakah pengguna ditemukan
if ($user === null) {
    echo "<p>User not found.</p>";
    exit();
}

// Tentukan path foto profil (jika ada)
$profilePicturePath = !empty($user['profile_picture']) ? $user['profile_picture'] : 'uploads/default_profile.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profil.css">
</head>
<body>
    <header>
        <a href="user_dashboard.php">
            <img src="logo.png" alt="Library Logo" class="logo">
        </a>
        <div class="header-links">
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

    <section>
        <div class="profile-container">
            <h2>User Profile</h2>
            <div class="profile-details">
                <img src="<?php echo htmlspecialchars($profilePicturePath); ?>" alt="Profile Picture" class="profile-img">
                <ul>
                    <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                    <li><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></li>
                    <li><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['tempat_tanggal_lahir']); ?></li>
                    <li><strong>Gender:</strong> <?php echo htmlspecialchars($user['jenis_kelamin']); ?></li>
                    <li><strong>Occupation:</strong> <?php echo htmlspecialchars($user['pekerjaan']); ?></li>
                    <li><strong>Address:</strong> <?php echo htmlspecialchars($user['alamat_tinggal']); ?></li>
                    <li><strong>Age:</strong> <?php echo htmlspecialchars($user['usia']); ?></li>
                    <li><strong>Last Education:</strong> <?php echo htmlspecialchars($user['pendidikan_terakhir']); ?></li>
                </ul>
            </div>

            <!-- Form untuk Upload Foto Profil -->
            <form action="upload_picture.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile_picture">Upload Foto Profil</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" required>
                </div>
                <button type="submit">Upload Foto</button>
            </form>
        </div>
    </section>
</body>
</html>