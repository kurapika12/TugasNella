<?php
include('db_config.php');

// Proses upload gambar atau video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
    $file = $_FILES['media'];
    $description = $_POST['description'];
    $theme = $_POST['theme'];  // Ambil kategori dari form

    // Tentukan direktori penyimpanan file
    $upload_dir = '../php/uploads/'; // Pastikan path relatif sesuai dengan struktur folder Anda
    $file_name = basename($file['name']);
    $upload_path = $upload_dir . $file_name;
    $file_type = strtolower(pathinfo($upload_path, PATHINFO_EXTENSION));

    // Validasi tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov'];
    if (in_array($file_type, $allowed_types)) {
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Simpan data file ke database
            $query = "INSERT INTO gallery (image_path, description, theme) VALUES (:image_path, :description, :theme)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':image_path', $upload_path);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':theme', $theme);  // Simpan tema ke database

            if ($stmt->execute()) {
                echo "<p>File berhasil diupload!</p>";
            } else {
                echo "<p>Gagal menyimpan data ke database.</p>";
            }
        } else {
            echo "<p>Gagal mengupload file.</p>";
        }
    } else {
        echo "<p>File yang diunggah tidak valid. Hanya file gambar dan video yang diperbolehkan.</p>";
    }

    // Redirect untuk mencegah resubmission form
    header('Location: gallery.php');
    exit;
}

// Ambil data gambar dan video yang sudah diunggah dengan filter tema
$theme_filter = isset($_GET['tema']) ? $_GET['tema'] : '';
// Debugging: Periksa apakah kategori diterima dengan benar
// var_dump($_GET);

if ($theme_filter) {
    $query = "SELECT * FROM gallery WHERE theme = :theme";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':theme', $theme_filter);
    $stmt->execute();
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Jika tidak ada filter, tampilkan semua dokumentasi
    $query = "SELECT * FROM gallery";
    $result = $pdo->query($query);
    $media = $result->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Dokumentasi</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }

        #caption {
            color: #ccc;
            text-align: center;
            padding: 10px 0;
            font-size: 20px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .gallery-item {
            width: 300px;
            text-align: center;
            margin-bottom: 20px;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Galeri Dokumentasi</h1>
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
        <h2>Unggah Dokumentasi Kegiatan</h2>
        <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <label for="media">Pilih Gambar/Video:</label>
            <input type="file" id="media" name="media" required>
            
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" required></textarea>
            
            <label for="theme">Kategori:</label>
            <select name="theme" id="theme" required>
                <option value="Workshop">Workshop</option>
                <option value="Seminar">Seminar</option>
                <option value="Meeting">Meeting</option>
                <!-- Tambahkan kategori lainnya sesuai kebutuhan -->
            </select>
            
            <button type="submit">Unggah</button>
        </form>
    </section>

    <section>
        <h2>Filter Berdasarkan Tema</h2>
        <form action="gallery.php" method="GET">
            <label for="filter">Pilih Tema:</label>
            <select name="tema" id="filter">
                <option value="">Semua</option>
                <option value="Workshop" <?php echo $theme_filter == 'Workshop' ? 'selected' : ''; ?>>Workshop</option>
                <option value="Seminar" <?php echo $theme_filter == 'Seminar' ? 'selected' : ''; ?>>Seminar</option>
                <option value="Meeting" <?php echo $theme_filter == 'Meeting' ? 'selected' : ''; ?>>Meeting</option>
                <!-- Tambahkan kategori lainnya -->
            </select>
            <button type="submit">Filter</button>
        </form>
    </section>

    <section>
        <h2>Dokumentasi yang Sudah Diupload</h2>
        <div class="gallery-container">
            <?php foreach ($media as $item) { ?>
                <div class="gallery-item">
                    <?php if (in_array(pathinfo($item['image_path'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                        <img src="<?php echo $item['image_path']; ?>" alt="Image" onclick="openModal('<?php echo $item['image_path']; ?>', '<?php echo $item['description']; ?>')" />
                    <?php } elseif (in_array(pathinfo($item['image_path'], PATHINFO_EXTENSION), ['mp4', 'mov'])) { ?>
                        <video controls>
                            <source src="<?php echo $item['image_path']; ?>" type="video/<?php echo pathinfo($item['image_path'], PATHINFO_EXTENSION); ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php } ?>
                    <p><?php echo $item['description']; ?></p>
                    <p><strong>Kategori:</strong> <?php echo $item['theme']; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Modal untuk gambar -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <script>
        function openModal(imagePath, description) {
            var modal = document.getElementById("imageModal");
            var img = document.getElementById("img01");
            var caption = document.getElementById("caption");
            
            img.src = imagePath;
            caption.innerHTML = description;
            
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
