<?php
include '../dashboard/partials/header.php';

// Daftar status untuk filter
$status_options = ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja'];

// Ambil filter dari URL
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Bangun query berdasarkan filter
$query = "SELECT * FROM tabel_alumni";
$report_title = "Menampilkan Semua Alumni";

if (!empty($filter_status)) {
    $query .= " WHERE status = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
    $report_title = "Menampilkan Alumni dengan Status: " . htmlspecialchars($filter_status);
}

$query .= " ORDER BY tahun_lulus DESC, nama ASC";
$result = mysqli_query($koneksi, $query);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Filter Berdasarkan Status</h6>
    </div>
    <div class="card-body">
        <form action="status.php" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="status" class="form-label">Pilih Status Alumni</label>
                <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                    <option value="">-- Tampilkan Semua --</option>
                    <?php foreach ($status_options as $stat) : ?>
                    <option value="<?= $stat ?>" <?= ($filter_status == $stat) ? 'selected' : '' ?>><?= $stat ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary"><?= $report_title ?></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
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
                        <td colspan="8" class="text-center">Tidak ada data alumni untuk status ini.</td>
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