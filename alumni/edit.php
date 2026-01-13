<?php
$_GET['page'] = 'Data Alumni';
include '../dashboard/partials/header.php';

// Daftar jurusan dan status untuk dropdown
$jurusan_options = ['RPL', 'TKJ', 'Keperawatan', 'Farmasi', 'Lainnya'];
$status_options = ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja'];

// Cek ID dari URL
if (!isset($_GET['id'])) {
    header("Location: alumni.php");
    exit;
}
$id = $_GET['id'];

// Ambil data alumni yang akan diedit
$query_get = "SELECT * FROM tabel_alumni WHERE id_alumni = $id";
$result_get = mysqli_query($koneksi, $query_get);
$data = mysqli_fetch_assoc($result_get);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $tahun_lulus = mysqli_real_escape_string($koneksi, $_POST['tahun_lulus']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query_update = "UPDATE tabel_alumni SET 
                        nis = '$nis', 
                        nama = '$nama', 
                        jurusan = '$jurusan', 
                        tahun_lulus = '$tahun_lulus', 
                        status = '$status', 
                        no_hp = '$no_hp', 
                        email = '$email', 
                        alamat = '$alamat' 
                    WHERE id_alumni = $id";

    if (mysqli_query($koneksi, $query_update)) {
        $_SESSION['pesan_sukses'] = "Data alumni berhasil diperbarui.";
        header("Location: alumni.php");
        exit;
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}
?>

<h1 class="mb-4">Edit Data Alumni</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Data</h6>
    </div>
    <div class="card-body">
        <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
        <?php endif; ?>

        <form action="edit.php?id=<?= $id ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis"
                            value="<?= htmlspecialchars($data['nis']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select class="form-select" id="jurusan" name="jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusan_options as $jur) : ?>
                            <option value="<?= $jur ?>" <?= ($data['jurusan'] == $jur) ? 'selected' : '' ?>><?= $jur ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                        <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" min="1990"
                            max="<?= date('Y') ?>" value="<?= htmlspecialchars($data['tahun_lulus']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Alumni</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($status_options as $stat) : ?>
                            <option value="<?= $stat ?>" <?= ($data['status'] == $stat) ? 'selected' : '' ?>>
                                <?= $stat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="tel" class="form-control" id="no_hp" name="no_hp"
                            value="<?= htmlspecialchars($data['no_hp']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($data['email']) ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat"
                            rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end">
                <a href="alumni.php" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>

<?php
include '../dashboard/partials/footer.php';
?>