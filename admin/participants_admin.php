<?php
// Include koneksi database
include('../php/db_config.php');

// Ambil data peserta dari database
$query = "SELECT * FROM participants";
$result = mysqli_query($conn, $query);

// Proses hapus peserta
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM participants WHERE id = $delete_id";
    mysqli_query($conn, $delete_query);
    header("Location: list_participants.php");  // Redirect setelah hapus
}
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
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['event_id']; ?></td>
                    <td>
                        <a href="list_participants.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </section>
</body>
</html>
