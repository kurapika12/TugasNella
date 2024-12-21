<?php
include('db_config.php');

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
            <li><a href="index.php">Home</a></li>
            <li><a href="list_events.php">Daftar Kegiatan</a></li>
            <li><a href="add_participant.php">Tambah Peserta</a></li>
            <li><a href="list_feedback.php">Daftar Feedback</a></li>
            <li><a href="gallery.php">Galeri</a></li>
            <li><a href="../admin/login.php" class="login-btn">LogIn</a></li>
        </ul>
    </nav>

    <section>
        <h2>Daftar Kegiatan yang Akan Datang</h2>
        <div class="table-container">
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
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="list_events.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this event?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>

<?php
// Hapus event jika parameter delete_id ada
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM events WHERE id = $delete_id";
    mysqli_query($conn, $delete_query);
    header("Location: list_events.php"); // Redirect setelah menghapus
    exit;
}
?>
