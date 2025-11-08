<?php
include('db_con.php');

$email = 'admin@gmail.com';
$username = 'admin';
$password = 'password'; 
$role = 'admin';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $username, $hashed_password, $role);

if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
