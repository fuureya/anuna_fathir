<?php
// Display latest news
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest News</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <h1>Latest News</h1>
    <p>Recent updates about the library...</p>
</body>
</html>
