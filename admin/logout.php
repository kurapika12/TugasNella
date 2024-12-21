<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman index.php
header("Location: ../php/index.php"); // Arahkan ke folder php
exit;
?>
