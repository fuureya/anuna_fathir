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

// Delete user from the database
$sql = "DELETE FROM users WHERE id = $user_id";
if ($conn->query($sql) === TRUE) {
    // Redirect to manage users page after deletion
    header("Location: manage_user.php");
    exit();
} else {
    echo "Error deleting user: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
