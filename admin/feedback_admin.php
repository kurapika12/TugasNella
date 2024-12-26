<?php
include('../php/db_config.php');

// Ambil data feedback dari database
$query = "SELECT f.id, f.rating, f.comment, e.title FROM feedback f JOIN events e ON f.event_id = e.id";
$result = $pdo->query($query);

// Proses tambah feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $event_id = $_POST['event_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Insert feedback ke database
    $insert_query = "INSERT INTO feedback (event_id, rating, comment) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute([$event_id, $rating, $comment]);

    header('Location: feedback_admin.php'); // Refresh halaman setelah submit
    exit;
}

// Proses hapus feedback
if (isset($_POST['delete_feedback'])) {
    $feedback_id = $_POST['delete_feedback'];

    // Hapus feedback dari database
    $delete_query = "DELETE FROM feedback WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([$feedback_id]);

    header('Location: feedback_admin.php'); // Refresh halaman setelah hapus
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Feedback</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Daftar Feedback</h1>
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
        <!-- Tabel untuk menampilkan Feedback -->
        <div class="feedback-table">
            <table>
                <thead>
                    <tr>
                        <th>Judul Kegiatan</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result) { ?>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['rating']; ?>/10</td>
                                <td><?php echo $row['comment']; ?></td>
                                <td>
                                    <!-- Tombol hapus -->
                                    <form method="POST" action="feedback_admin.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback ini?')">
                                        <input type="hidden" name="delete_feedback" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="delete-btn">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">Tidak ada feedback tersedia.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
