<?php
include('../php/db_config.php');

// Proses update data kegiatan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_event'])) {
    $id = $_POST['event_id'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    // Update data ke database
    $update_query = "UPDATE events SET title = ?, date = ?, location = ? WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute([$title, $date, $location, $id]);

    header("Location: event_admin.php"); // Redirect setelah update
    exit;
}

// Proses hapus data kegiatan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_event'])) {
    $id = $_POST['event_id'];

    // Hapus data dari database
    $delete_query = "DELETE FROM events WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([$id]);

    header("Location: event_admin.php"); // Redirect setelah hapus
    exit;
}

// Query untuk mengambil data kegiatan
$query = "SELECT * FROM events";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kegiatan</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Daftar Kegiatan</h1>
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
        <h2>Daftar Kegiatan yang Akan Datang</h2>
        <div class="table-container">
            <form method="POST" action="event_admin.php">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                            </td>
                            <td>
                                <input type="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>" required>
                            </td>
                            <td>
                                <input type="text" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" required>
                            </td>
                            <td>
                                <button type="submit" name="update_event" class="btn-edit">Simpan</button>
                                <button type="submit" name="delete_event" class="btn-delete" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">Hapus</button>
                                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </section>
</body>
</html>
