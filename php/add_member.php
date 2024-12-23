<?php
include('db_config.php');

// Proses tambah anggota
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_member'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $role = $_POST['role']; 

    // Insert anggota ke database
    $insert_query = "INSERT INTO members (name, email, contact, address, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute([$name, $email, $contact, $address, $role]);

    header('Location: list_members.php'); // Redirect ke halaman daftar anggota setelah submit
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Tambah Anggota Baru</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add_event.php">Tambah Kegiatan</a></li>
            <li><a href="list_events.php">Daftar Kegiatan</a></li>
            <li><a href="add_member.php">Tambah Anggota</a></li>
            <li><a href="list_members.php">Daftar Anggota</a></li>
            <li><a href="add_participant.php">Tambah Peserta</a></li>
            <li><a href="list_participants.php">Daftar Peserta</a></li>
            <li><a href="list_feedback.php">Daftar Feedback</a></li>
            <li><a href="gallery.php">Galeri</a></li>
        </ul>
    </nav>

    <section>
        <h2>Formulir Tambah Anggota</h2>
        <form method="POST" action="add_member.php">
            <label for="name">Nama Anggota:</label>
            <input type="text" name="name" id="name" required>
            <br>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="member">Member</option>
            </select>
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <br>

            <label for="contact">Nomor Telepon:</label>
            <input type="text" name="contact" id="contact" required>
            <br>

            <label for="address">Alamat:</label>
            <textarea name="address" id="address" rows="4"></textarea>
            <br>

            <button type="submit" name="submit_member">Tambah Anggota</button>
        </form>
    </section>
</body>
</html>