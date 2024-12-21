<?php
session_start();
include('../php/db_config.php');

// Cek apakah form login sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari admin dengan username yang diberikan
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika admin ditemukan dan password cocok
    if ($admin && password_verify($password, $admin['password'])) {
        // Set session dan arahkan ke dashboard admin
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        // Jika login gagal, set pesan error
        $error_message = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Login Admin</h1>
    </header>
    <section>        
        <!-- Tampilkan alert jika login gagal -->
        <?php if (isset($error_message)): ?>
            <div class="alert" style="color: red;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>

            <button type="submit">Login</button>
        </form>
    </section>
</body>
</html>
