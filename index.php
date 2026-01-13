<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: dashboard/index.php");
    exit;
}

// Jika belum, redirect ke halaman login
header("Location: auth/login.php");
exit;
?>