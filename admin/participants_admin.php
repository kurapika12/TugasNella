<?php
// Include koneksi database
include('../php/db_config.php');

// Proses hapus peserta
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM participants WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([$delete_id]);

    echo "<script>alert('Peserta berhasil dihapus');</script>";
    header("Location: participants_admin.php");
    exit;
}

// Ambil data peserta beserta nama kegiatan
$query = "
    SELECT participants.id, participants.name, participants.email, events.title AS event_title
    FROM participants
    JOIN events ON participants.event_id = events.id
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Daftar Peserta Kegiatan</h1>
    </header>

    <!-- Navbar -->
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

    <!-- Daftar Peserta -->
    <section>
        <h2>Peserta yang Terdaftar</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Email</th>
                    <th>Kegiatan</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr id="participant-<?php echo $row['id']; ?>">
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['event_title']; ?></td>
                    <td>
                        <form method="POST" action="participants_admin.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </section>

</body>
</html>
