<?php
// File: config/koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "simalumni";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk base_url
function base_url($url = null) {
    // Pastikan path ini sesuai dengan nama folder proyek Anda di htdocs
    // Spasi di URL diwakili oleh %20
    $base_url = "http://localhost/SIMALUMNI%20SMK"; 
    if ($url != null) {
        return $base_url . "/" . ltrim($url, '/');
    } else {
        return $base_url;
    }
}
?>