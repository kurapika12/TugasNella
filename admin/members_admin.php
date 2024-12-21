<?php
// Koneksi database
include('../php/db_config.php');

// Ambil daftar anggota
$query = "SELECT * FROM members";
$result = mysqli_query($conn, $query);

// Proses hapus anggota
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM members WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Anggota berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus anggota');</script>";
    }
    header("Location: list_member.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Daftar Anggota Komunitas</h1>
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
        <h2>Anggota yang Terdaftar</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['contact']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="edit_member.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <script>
        function confirmDelete(id) {
            const confirmation = confirm("Apakah Anda yakin ingin menghapus anggota ini?");
            if (confirmation) {
                window.location.href = `list_member.php?delete_id=${id}`;
            }
        }
    </script>
</body>
</html>
