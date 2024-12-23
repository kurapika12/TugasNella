<?php
// Pastikan file db_config.php disertakan dengan benar
include('../php/db_config.php');

// Proses untuk menambahkan event baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $theme = $_POST['theme'];

    // Menangani upload poster
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $poster_name = $_FILES['poster']['name'];
        $poster_tmp = $_FILES['poster']['tmp_name'];
        $poster_path = 'uploads/' . $poster_name;
        
        // Memindahkan file poster ke folder uploads
        if (move_uploaded_file($poster_tmp, $poster_path)) {
            $query = "INSERT INTO events (title, date, time, location, theme, poster) 
                      VALUES ('$title', '$date', '$time', '$location', '$theme', '$poster_name')";
            mysqli_query($conn, $query);
            header("Location: index.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Tambah Kegiatan</h1>
    </header>
    
    <nav>
        <ul>
            
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
