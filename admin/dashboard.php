<?php
include('../php/db_config.php');

// Ambil statistik jumlah kegiatan, peserta, anggota, dan feedback
$events_query = "SELECT COUNT(*) AS event_count FROM events";
$participants_query = "SELECT COUNT(*) AS participant_count FROM participants";
$members_query = "SELECT COUNT(*) AS member_count FROM members";
$feedback_query = "SELECT COUNT(*) AS feedback_count FROM feedback";

$events = $pdo->query($events_query)->fetch(PDO::FETCH_ASSOC);
$participants = $pdo->query($participants_query)->fetch(PDO::FETCH_ASSOC);
$members = $pdo->query($members_query)->fetch(PDO::FETCH_ASSOC);
$feedback = $pdo->query($feedback_query)->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
    </header>

    <nav>
        <ul>
            <li><a href="dashboard.php">Tambah Kegiatan</a></li>
            <li><a href="event_admin.php">Daftar Kegiatan</a></li>
            <li><a href="member_add.php">Tambah Anggota</a></li>
            <li><a href="members_admin.php">Daftar Anggota</a></li>
            <li><a href="participants_admin.php">Daftar Peserta</a></li>
            <li><a href="feedback_admin.php">Daftar Feedback</a></li>
            <li><a href="gallery_admin.php">Galeri</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>

    <section class="form-section">
        <h2>Form Tambah Kegiatan</h2>
        <form action="add_event.php" method="POST" enctype="multipart/form-data">
            <label for="title">Judul Kegiatan:</label>
            <input type="text" id="title" name="title" required>

            <label for="date">Tanggal:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Waktu:</label>
            <input type="time" id="time" name="time" required>

            <label for="location">Lokasi:</label>
            <input type="text" id="location" name="location" required>

            <label for="theme">Tema (Opsional):</label>
            <input type="text" id="theme" name="theme">

            <label for="poster">Poster Kegiatan:</label>
            <input type="file" id="poster" name="poster" accept="image/*" required>

            <button type="submit">Tambah Kegiatan</button>
        </form>
    </section>
</body>
</html>
