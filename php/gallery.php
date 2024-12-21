<?php
include('db_config.php');

// Proses upload gambar atau video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
    $file = $_FILES['media'];
    $description = $_POST['description'];

    // Tentukan direktori penyimpanan file
    $upload_dir = 'uploads/';
    $file_name = basename($file['name']);
    $upload_path = $upload_dir . $file_name;
    $file_type = strtolower(pathinfo($upload_path, PATHINFO_EXTENSION));

    // Validasi tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov'];
    if (in_array($file_type, $allowed_types)) {
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Simpan data file ke database
            $query = "INSERT INTO gallery (image_path, description) VALUES (:image_path, :description)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':image_path', $upload_path);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "<p>File berhasil diupload!</p>";
            } else {
                echo "<p>Gagal menyimpan data ke database.</p>";
            }
        } else {
            echo "<p>Gagal mengupload file.</p>";
        }
    } else {
        echo "<p>File yang diunggah tidak valid. Hanya file gambar dan video yang diperbolehkan.</p>";
    }

     // Redirect untuk mencegah resubmission form
     header('Location: gallery.php');
     exit;
}

// Ambil data gambar dan video yang sudah diunggah
$query = "SELECT * FROM gallery";
$result = $pdo->query($query);
$media = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Dokumentasi</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Galeri Dokumentasi</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="list_events.php">Daftar Kegiatan</a></li>
            <li><a href="add_participant.php">Tambah Peserta</a></li>
            <li><a href="list_feedback.php">Daftar Feedback</a></li>
            <li><a href="gallery.php">Galeri</a></li>
            <li><a href="../admin/login.php" class="login-btn">LogIn</a></li>
        </ul>
    </nav>

    <section class="form-section">
        <h2>Unggah Dokumentasi Kegiatan</h2>
        <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <label for="media">Pilih Gambar/Video:</label>
            <input type="file" id="media" name="media" required>
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit">Unggah</button>
        </form>
    </section>

    <section>
        <h2>Dokumentasi yang Sudah Diupload</h2>
        <div class="gallery-container">
            <?php foreach ($media as $item) { ?>
                <div class="gallery-item">
                    <?php if (in_array(pathinfo($item['image_path'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                        <img src="<?php echo $item['image_path']; ?>" alt="Image" />
                    <?php } elseif (in_array(pathinfo($item['image_path'], PATHINFO_EXTENSION), ['mp4', 'mov'])) { ?>
                        <video controls>
                            <source src="<?php echo $item['image_path']; ?>" type="video/<?php echo pathinfo($item['image_path'], PATHINFO_EXTENSION); ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php } ?>
                    <p><?php echo $item['description']; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>
