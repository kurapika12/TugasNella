<?php
include('db_config.php');

// Ambil daftar acara untuk dropdown
$query = "SELECT * FROM events";
$result = $pdo->query($query);
$events = $result->fetchAll(PDO::FETCH_ASSOC);

// Proses tambah peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $event_id = $_POST['event_id'];

    // Menyimpan data peserta ke dalam database
    $query = "INSERT INTO participants (name, email, event_id) VALUES (:name, :email, :event_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':event_id', $event_id);

    if ($stmt->execute()) {
        echo "<p>Peserta berhasil ditambahkan!</p>";
    } else {
        echo "<p>Gagal menambahkan peserta.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Tambah Peserta ke Kegiatan</h1>
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
        <h2>Formulir Pendaftaran Peserta</h2>
        <form action="add_participant.php" method="POST">
            <label for="name">Nama Peserta:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Peserta:</label>
            <input type="email" id="email" name="email" required>

            <label for="event_id">Pilih Kegiatan:</label>
            <select id="event_id" name="event_id" required>
                <?php foreach ($events as $event) { ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo $event['title']; ?> - <?php echo $event['date']; ?></option>
                <?php } ?>
            </select>

            <button type="submit">Tambah Peserta</button>
        </form>
    </section>  
</body>
</html>
