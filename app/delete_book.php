<?php
session_start();
require 'db_con.php'; // Include koneksi database

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get book ID from URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
} else {
    header("Location: manage_books.php");
    exit();
}

// Delete book from database
$query = "DELETE FROM books WHERE buku_id = $book_id";
if (mysqli_query($conn, $query)) {
    // Redirect to manage books page after deletion
    header("Location: manage_books.php");
    exit();
} else {
    echo "Error deleting book: " . mysqli_error($conn);
}
?>
