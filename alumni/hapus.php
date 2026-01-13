<?php
session_start();
require '../config/koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cek ID dari URL
if (!isset($_GET['id'])) {
    header("Location: alumni.php");
    exit;
}
$id = $_GET['id'];

// Query untuk hapus data
$query = "DELETE FROM tabel_alumni WHERE id_alumni = $id";

if (mysqli_query($koneksi, $query)) {
    $_SESSION['pesan_sukses'] = "Data alumni berhasil dihapus.";
} else {
    // Jika ingin menampilkan error, bisa ditambahkan di sini
    // $_SESSION['pesan_error'] = "Gagal menghapus data: " . mysqli_error($koneksi);
}

header("Location: alumni.php");
exit;
?>