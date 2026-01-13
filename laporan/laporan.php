<?php
$_GET['page'] = 'Laporan';
include '../dashboard/partials/header.php';

// Ambil filter dari URL
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$filter_value = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

// Ambil daftar unik untuk filter dropdown
$jurusan_list = mysqli_query($koneksi, "SELECT DISTINCT jurusan FROM tabel_alumni ORDER BY jurusan");
$tahun_list = mysqli_query($koneksi, "SELECT DISTINCT tahun_lulus FROM tabel_alumni ORDER BY tahun_lulus DESC");

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

<h1 class="mb-4">Laporan Alumni</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="laporan.php" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="filter_type" class="form-label">Filter Berdasarkan</label>
                <select class="form-select" id="filter_type" name="filter_type" onchange="this.form.submit()">
                    <option value="">-- Tampilkan Semua --</option>
                    <option value="jurusan" <?= ($filter_type == 'jurusan') ? 'selected' : '' ?>>Jurusan</option>
                    <option value="tahun" <?= ($filter_type == 'tahun') ? 'selected' : '' ?>>Tahun Lulus</option>
                </select>
            </div>
            <div class="col-md-4">
                <?php if ($filter_type == 'jurusan') : ?>
                <label for="filter_value" class="form-label">Pilih Jurusan</label>
                <select class="form-select" name="filter_value" onchange="this.form.submit()">
                    <option value="">-- Semua Jurusan --</option>
                    <?php while ($row = mysqli_fetch_assoc($jurusan_list)) : ?>
                    <option value="<?= $row['jurusan'] ?>" <?= ($filter_value == $row['jurusan']) ? 'selected' : '' ?>>
                        <?= $row['jurusan'] ?></option>
                    <?php endwhile; ?>
                </select>
                <?php elseif ($filter_type == 'tahun') : ?>
                <label for="filter_value" class="form-label">Pilih Tahun Lulus</label>
                <select class="form-select" name="filter_value" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun --</option>
                    <?php while ($row = mysqli_fetch_assoc($tahun_list)) : ?>
                    <option value="<?= $row['tahun_lulus'] ?>"
                        <?= ($filter_value == $row['tahun_lulus']) ? 'selected' : '' ?>><?= $row['tahun_lulus'] ?>
                    </option>
                    <?php endwhile; ?>
                </select>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <a href="cetak.php?filter_type=<?= $filter_type ?>&filter_value=<?= $filter_value ?>" target="_blank"
                    class="btn btn-success">
                    <i class="bi bi-printer-fill"></i> Cetak Laporan
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= $report_title ?></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="table-dark">
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
                        <td colspan="8" class="text-center">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../dashboard/partials/footer.php';
?>