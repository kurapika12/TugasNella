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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        .member-card {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            display: inline-grid;
            width: 250px;
            text-align: center;
            cursor: pointer;
        }
        .member-card h3 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #ccc;
        }
        .member-card p {
            font-size: 0.9em;
            margin: 5px 0;
            color: #ccc;
        }
        .btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        /* Styling for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            overflow: auto;
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
        <div class="member-cards-container">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="member-card" onclick="openModal(<?php echo $row['id']; ?>)">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p>Role: <?php echo htmlspecialchars($row['role']); ?></p>
                    <form method="POST" action="members_admin.php" style="display:inline;">
                        <input type="hidden" name="member_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_member" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">Hapus</button>
                    </form>
                </div>

                <!-- Modal for Member Details -->
                <div id="modal-<?php echo $row['id']; ?>" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal(<?php echo $row['id']; ?>)">&times;</span>
                        <h2>Profil Anggota</h2>
                        <p><strong>Nama:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Kontak:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
                        <p><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <script>
        // Function to open modal
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = "block";
        }

        // Function to close modal
        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = "none";
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            var modals = document.getElementsByClassName("modal");
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
