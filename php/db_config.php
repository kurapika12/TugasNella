<?php
// Konfigurasi untuk PDO
$host = 'localhost'; // atau sesuaikan dengan host Anda
$db = 'community_schedule_management'; // nama database
$user = 'root'; // nama pengguna database
$pass = ''; // password database (kosong jika tidak ada)

// Membuat koneksi menggunakan PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal dengan PDO: " . $e->getMessage();
    exit;
}

// Koneksi dengan MySQLi
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal dengan MySQLi: " . mysqli_connect_error());
}
?>
