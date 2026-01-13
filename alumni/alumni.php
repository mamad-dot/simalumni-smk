<?php
$_GET['page'] = 'Data Alumni';
// Path ke header.php diubah agar sesuai
include '../dashboard/partials/header.php';

// Logika Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = '';
if ($search) {
    $where = "WHERE nis LIKE '%$search%' OR nama LIKE '%$search%' OR jurusan LIKE '%$search%' OR tahun_lulus LIKE '%$search%'";
}

// Query untuk mengambil data alumni
$query = "SELECT * FROM tabel_alumni $where ORDER BY tahun_lulus DESC, nama ASC";
$result = mysqli_query($koneksi, $query);
?>

<h1 class="mb-4">Data Alumni</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Alumni</h6>
            <a href="tambah.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Form Pencarian -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form action="alumni.php" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari NIS, Nama, Jurusan..."
                            value="<?= $search ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_SESSION['pesan_sukses'])) : ?>
        <div class="alert alert-success" role="alert">
            <?= $_SESSION['pesan_sukses'] ?>
        </div>
        <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

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
                        <th>Aksi</th>
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
                        <td>
                            <a href="edit.php?id=<?= $row['id_alumni'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="hapus.php?id=<?= $row['id_alumni'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data alumni.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Path ke footer.php diubah agar sesuai
include '../dashboard/partials/footer.php';
?>