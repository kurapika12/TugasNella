<?php
include('../php/db_config.php');

// Cek apakah pengguna sudah login sebagai admin
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Proses hapus gambar
if (isset($_GET['delete'])) {
    $image_id = $_GET['delete'];

    // Hapus gambar dari database
    $delete_query = "DELETE FROM gallery WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([$image_id]);

    // Redirect ke halaman gallery setelah menghapus
    header("Location: gallery.php");
    exit;
}

// Ambil data gambar dari database
$query = "SELECT * FROM gallery";
$result = $pdo->query($query);

if ($result) {
    $images = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Gagal mengambil data gambar.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Dokumentasi - Admin</title>
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
        <h2>Dokumentasi Kegiatan</h2>
        <div class="gallery-container">
            <?php foreach ($images as $image) { ?>
                <div class="gallery-item">
                    <img src="<?php echo $image['image_path']; ?>" alt="Image" />
                    <p><?php echo $image['description']; ?></p>
                    <a href="gallery.php?delete=<?php echo $image['id']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')">Hapus</a>
                </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>
