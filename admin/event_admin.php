<?php
include('../php/db_config.php');

// Mengaktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Menangani penghapusan event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "DELETE FROM events WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Event berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus event: ' . mysqli_error($conn)]);
    }
    exit;  // Pastikan script tidak menghasilkan output lainnya setelah pengiriman JSON
}

// Query untuk mengambil data kegiatan
$query = "SELECT * FROM events";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kegiatan</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            text-align: center;
            padding: 0.5rem ;
            background-color: #007BFF;
            color: #fff;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            gap: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card-location, .card-date {
            font-size: 14px;
            color: #555;
        }
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background-color: #e60000;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            position: relative;
        }
        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .modal-body {
            font-size: 16px;
            color: #555;
        }
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            background: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-close:hover {
            background: #e60000;
        }
    </style>
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

    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card" onclick="showModal(<?php echo htmlspecialchars(json_encode($row)); ?>)"> 
                <img src="../admin/uploads/<?php echo htmlspecialchars($row['poster']); ?>" alt="Gambar Kegiatan">
                <div class="card-body">
                    <div class="card-title"><?php echo htmlspecialchars($row['title']); ?></div>
                    <div class="card-date">Tanggal: <?php echo htmlspecialchars($row['date']); ?></div>
                    <div class="card-time">Waktu: <?php echo htmlspecialchars($row['time']); ?></div>
                    <div class="card-location">Lokasi: <?php echo htmlspecialchars($row['location']); ?></div>
                    <div class="card-theme">Tema: <?php echo htmlspecialchars($row['theme']); ?></div>
                    <button class="delete-btn" onclick="deleteEvent(<?php echo $row['id']; ?>)">Hapus</button>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <div class="modal-header" id="modalTitle"></div>
            <img id="modalPoster" src="../admin/uploads/" alt="Gambar Detail" style="width: 100%; border-radius: 10px; margin-bottom: 10px;">
            <div class="modal-body">
                <p id="modalDate"></p>
                <p id="modalTime"></p>
                <p id="modalLocation"></p>
                <p id="modalTheme"></p>
                <p id="modalDescription"></p>
            </div>
        </div>
    </div>

    <script>
        function showModal(data) {
            console.log(data); // Cek data yang dikirimkan
            document.getElementById('modalPoster').src = '../admin/uploads/' + data.poster;
            document.getElementById('modalTitle').textContent = data.title;
            document.getElementById('modalDate').textContent = 'Tanggal: ' + data.date;
            document.getElementById('modalTime').textContent = 'Waktu: ' + data.time;
            document.getElementById('modalLocation').textContent = 'Lokasi: ' + data.location;
            document.getElementById('modalTheme').textContent = 'Tema: ' + data.theme;
            document.getElementById('modalDescription').textContent = data.description;
            document.getElementById('detailModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        function deleteEvent(id) {
            if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
                // Membuat data form-url-encoded
                const formData = new URLSearchParams();
                formData.append('id', id);

                fetch('event_admin.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload(); // Refresh halaman setelah penghapusan
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => alert('Terjadi kesalahan: ' + error));
            }
        }

    </script>
</body>
</html>
