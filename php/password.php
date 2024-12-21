<?php
include('db_config.php');

$username = "admin";
$password = "nela";  // Gantilah dengan password yang aman

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $pdo->prepare($query);
$stmt->execute([$username, $hashed_password]);

echo "Admin berhasil ditambahkan.";
?>
