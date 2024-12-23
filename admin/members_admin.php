<?php
// Koneksi database
include('../php/db_config.php');

// Proses update anggota
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_member'])) {
    $id = $_POST['member_id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update_query = "UPDATE members SET name = ?, contact = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute([$name, $contact, $email, $role, $id]);

    echo "<script>alert('Data anggota berhasil diperbarui');</script>";
    header("Location: members_admin.php");
    exit;
}

// Proses hapus anggota
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_member'])) {
    $id = $_POST['member_id'];

    $delete_query = "DELETE FROM members WHERE id = ?";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute([$id]);

    echo "<script>alert('Anggota berhasil dihapus');</script>";
    header("Location: members_admin.php");
    exit;
}

// Ambil daftar anggota
$query = "SELECT * FROM members";
$result = mysqli_query($conn, $query);
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
        <form method="POST" action="members_admin.php">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                        </td>
                        <td>
                            <input type="text" name="contact" value="<?php echo htmlspecialchars($row['contact']); ?>" required>
                        </td>
                        <td>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                        <td>
                            <input type="text" name="role" value="<?php echo htmlspecialchars($row['role']); ?>" required>
                        </td>
                        <td>
                            <button type="submit" name="update_member" class="btn-edit">Simpan</button>
                            <button type="submit" name="delete_member" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">Hapus</button>
                            <input type="hidden" name="member_id" value="<?php echo $row['id']; ?>">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    </section>
</body>
</html>
