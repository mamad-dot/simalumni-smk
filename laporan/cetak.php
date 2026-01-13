<?php
require '../config/koneksi.php';

// Ambil filter dari URL
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$filter_value = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

// Bangun query berdasarkan filter
$query = "SELECT * FROM tabel_alumni";
$where = [];
$report_title = "Laporan Semua Alumni";

if ($filter_type == 'jurusan' && !empty($filter_value)) {
    $where[] = "jurusan = '" . mysqli_real_escape_string($koneksi, $filter_value) . "'";
    $report_title = "Laporan Alumni Jurusan " . htmlspecialchars($filter_value);
} elseif ($filter_type == 'tahun' && !empty($filter_value)) {
    $where[] = "tahun_lulus = '" . mysqli_real_escape_string($koneksi, $filter_value) . "'";
    $report_title = "Laporan Alumni Lulusan Tahun " . htmlspecialchars($filter_value);
}

if (!empty($where)) {
    $query .= " WHERE " . implode(' AND ', $where);
}
$query .= " ORDER BY tahun_lulus DESC, nama ASC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $report_title ?></title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
    }

    .header p {
        margin: 5px 0 0;
        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #000;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>SIMALUMNI SMK</h1>
        <p>Sistem Informasi Manajemen Alumni SMK</p>
        <hr>
        <h2><?= $report_title ?></h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Tahun Lulus</th>
                <th>Status</th>
                <th>No. HP</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nis'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['jurusan'] ?></td>
                <td><?= $row['tahun_lulus'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['no_hp'] ?></td>
                <td><?= $row['email'] ?></td>
            </tr>
            <?php endwhile; ?>
            <?php else : ?>
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data untuk ditampilkan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
    window.onload = function() {
        window.print();
    }
    </script>
</body>

</html>