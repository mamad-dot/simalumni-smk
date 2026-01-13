<?php
session_start();
require_once realpath(__DIR__ . '/../../config/koneksi.php');

// Proteksi halaman
if (!isset($_SESSION['login'])) {
    header("Location: " . base_url('auth/login.php'));
    exit;
}

// Ambil nama folder dan file untuk active link yang lebih akurat
$current_folder = basename(dirname($_SERVER['SCRIPT_NAME']));
$current_page = basename($_SERVER['SCRIPT_NAME']);

// Fungsi untuk menentukan judul halaman
function get_page_title($folder, $page) {
    if ($folder == 'dashboard') return 'Dashboard';
    if ($folder == 'alumni') {
        if ($page == 'tambah.php') return 'Tambah Data Alumni';
        if ($page == 'edit.php') return 'Edit Data Alumni';
        return 'Data Alumni';
    }
    if ($folder == 'status') return 'Status Alumni';
    if ($folder == 'laporan') return 'Laporan';
    if ($folder == 'pengaturan') return 'Pengaturan'; // Ditambahkan
    return 'SIMALUMNI';
}
$page_title = get_page_title($current_folder, $current_page);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - SIMALUMNI SMK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-primary flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?= base_url('dashboard/index.php') ?>">
            <div class="fw-bold"><i class="bi bi-mortarboard-fill"></i> SIMALUMNI SMK</div>
            <small style="font-size: 0.7em;">Sistem Informasi Manajemen Alumni</small>
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex align-items-center">
                <div class="nav-link px-3 text-white dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('auth/logout.php') ?>"><i
                                    class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_folder == 'dashboard') ? 'active' : '' ?>"
                                href="<?= base_url('dashboard/index.php') ?>">
                                <i class="bi bi-house-door"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_folder == 'alumni') ? 'active' : '' ?>"
                                href="<?= base_url('alumni/alumni.php') ?>">
                                <i class="bi bi-people"></i> Data Alumni
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_folder == 'status') ? 'active' : '' ?>"
                                href="<?= base_url('status/status.php') ?>">
                                <i class="bi bi-graph-up"></i> Status Alumni
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_folder == 'laporan') ? 'active' : '' ?>"
                                href="<?= base_url('laporan/laporan.php') ?>">
                                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- Link diubah dan diberi kondisi active -->
                            <a class="nav-link <?= ($current_folder == 'pengaturan') ? 'active' : '' ?>"
                                href="<?= base_url('pengaturan/index.php') ?>">
                                <i class="bi bi-gear"></i> Pengaturan
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2"><?= $page_title ?></h1>
                </div>