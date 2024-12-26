<?php
include('db_config.php');

// Ambil daftar acara untuk dropdown
$query = "SELECT * FROM events";
$result = $pdo->query($query);
$events = $result->fetchAll(PDO::FETCH_ASSOC);

// Proses tambah peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $event_id = $_POST['event_id'];

    // Menyimpan data peserta ke dalam database
    $query = "INSERT INTO participants (name, email, event_id) VALUES (:name, :email, :event_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':event_id', $event_id);

    if ($stmt->execute()) {
        // Ambil nama kegiatan yang baru didaftarkan
        $event_query = "SELECT title FROM events WHERE id = :event_id";
        $event_stmt = $pdo->prepare($event_query);
        $event_stmt->bindParam(':event_id', $event_id);
        $event_stmt->execute();
        $event = $event_stmt->fetch(PDO::FETCH_ASSOC);

        // Menyimpan nama kegiatan ke session untuk diakses oleh JavaScript
        echo "<script>
                var eventTitle = '" . addslashes($event['title']) . "';
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = document.getElementById('successModal');
                    var modalMessage = document.getElementById('modalMessage');
                    modalMessage.innerText = 'Peserta berhasil mendaftar untuk kegiatan: ' + eventTitle;
                    modal.style.display = 'block';
                });
            </script>";
     // Redirect setelah sukses untuk menghindari resubmission data
     header("Location: " . $_SERVER['PHP_SELF']);
     exit;
        } else {
            echo "<script>
                alert('Gagal menambahkan peserta.');
            </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
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
        <h1>Tambah Peserta ke Kegiatan</h1>
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
        <h2>Formulir Pendaftaran Peserta</h2>
        <form action="add_participant.php" method="POST">
            <label for="name">Nama Peserta:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Peserta:</label>
            <input type="email" id="email" name="email" required>

            <label for="event_id">Pilih Kegiatan:</label>
            <select id="event_id" name="event_id" required>
                <?php foreach ($events as $event) { ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo $event['title']; ?> - <?php echo $event['date']; ?></option>
                <?php } ?>
            </select>

            <button type="submit">Tambah Peserta</button>
        </form>
    </section>

    <!-- Modal for displaying success message -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Function to close the modal
        function closeModal() {
            document.getElementById('successModal').style.display = "none";
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('successModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
