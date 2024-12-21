<?php
include('db_config.php');

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
            <li><a href="index.php">Home</a></li>
            <li><a href="list_events.php">Daftar Kegiatan</a></li>
            <li><a href="add_participant.php">Tambah Peserta</a></li>
            <li><a href="list_feedback.php">Daftar Feedback</a></li>
            <li><a href="gallery.php">Galeri</a></li>
            <li><a href="../admin/login.php" class="login-btn">LogIn</a></li>
        </ul>
    </nav>

    <section>
        
        <!-- Form untuk menambah feedback -->
        <h2>Feedback Kegiatan</h2>
        <form method="POST" action="list_feedback.php">
            <label for="event_id">Pilih Kegiatan:</label>
            <select name="event_id" id="event_id" required>
                <?php
                $events_query = "SELECT id, title FROM events";
                $events_result = $pdo->query($events_query);
                while ($event = $events_result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $event['id'] . "'>" . $event['title'] . "</option>";
                }
                ?>
            </select>
            <br>

            <label for="rating">Rating (1-5):</label>
            <input type="number" name="rating" id="rating" min="1" max="5" required>
            <br>

            <label for="comment">Komentar:</label>
            <textarea name="comment" id="comment" rows="4" required></textarea>
            <br>

            <button type="submit" name="submit_feedback">Kirim Feedback</button>
        </form>

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
                                <td><?php echo $row['rating']; ?>/5</td>
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
