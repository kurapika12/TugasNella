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

    header('Location: list_feedback.php'); // Refresh halaman setelah submit
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
        <!-- Daftar Feedback -->
        <div class="feedback-table">
            <table>
                <thead>
                    <tr>
                        <th>Judul Kegiatan</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result) { ?>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['rating']; ?>/10</td>
                                <td><?php echo $row['comment']; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3">Tidak ada feedback tersedia.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
