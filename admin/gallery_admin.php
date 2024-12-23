<?php
include('../php/db_config.php');

// Proses hapus gambar atau video
if (isset($_GET['delete'])) {
    $media_id = $_GET['delete'];

    // Ambil path file yang akan dihapus dari database
    $query = "SELECT image_path FROM gallery WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $media_id);
    $stmt->execute();
    $media = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($media) {
        $file_path = $media['image_path'];

        // Hapus file dari server
        if (file_exists($file_path)) {
            unlink($file_path); // Menghapus file dari server
        }

        // Hapus data dari database
        $delete_query = "DELETE FROM gallery WHERE id = :id";
        $delete_stmt = $pdo->prepare($delete_query);
        $delete_stmt->bindParam(':id', $media_id);
        if ($delete_stmt->execute()) {
            echo "<p>File berhasil dihapus.</p>";
        } else {
            echo "<p>Gagal menghapus file dari database.</p>";
        }
    } else {
        echo "<p>File tidak ditemukan.</p>";
    }

    // Redirect kembali ke halaman galeri setelah penghapusan
    header('Location: gallery_admin.php');
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

    <section>
        <h2>Dokumentasi yang Sudah Diupload</h2>
        <div class="gallery-container">
            <?php foreach ($media as $item) { ?>
                <div class="gallery-item">
                    <?php if (in_array(pathinfo($item['image_path'], PATHINFO_EXTENSION), ['jpg', 'JPG', 'jpeg', 'png', 'gif'])) { ?>
                        <img src="<?php echo $item['image_path']; ?>" alt="Image" />
                    <?php } elseif (in_array(pathinfo($item['image_path'], PATHINFO_EXTENSION), ['mp4', 'mov'])) { ?>
                        <video controls>
                            <source src="<?php echo $item['image_path']; ?>" type="video/<?php echo pathinfo($item['image_path'], PATHINFO_EXTENSION); ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php } ?>
                    <p><?php echo $item['description']; ?></p>
                    <!-- Tombol hapus -->
                    <a href="gallery_admin.php?delete=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus file ini?')">Hapus</a>
                </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>
