<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include('db_con.php');

// Get user ID from URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    header("Location: manage_user.php");
    exit();
}

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="css/manage_user.css">
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
            <h1>User Details</h1>
            <p><strong>Name:</strong> <?php echo $user['fullname']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
            <p><strong>Gender:</strong> <?php echo $user['jenis_kelamin']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['alamat_tinggal']; ?></p>
            <p><strong>Occupation:</strong> <?php echo $user['pekerjaan']; ?></p>
            <p><strong>Birthdate:</strong> <?php echo $user['tempat_tanggal_lahir']; ?></p>
            <p><strong>Last Education:</strong> <?php echo $user['pendidikan_terakhir']; ?></p>

            <!-- Edit User Button -->
            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="edit-button">Edit User</a>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
