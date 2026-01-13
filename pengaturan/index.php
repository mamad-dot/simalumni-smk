<?php
// Menggunakan 'index.php' sebagai nama file, jadi kita bisa menamainya 'Pengaturan'
$_GET['page'] = 'Pengaturan';
include '../dashboard/partials/header.php';

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Ambil data user saat ini
$query_user = mysqli_query($koneksi, "SELECT * FROM tabel_users WHERE id_user = '$id_user'");
$user_data = mysqli_fetch_assoc($query_user);

$error = '';
$sukses = '';

// Proses form jika ada data yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi dasar
    if (empty($username)) {
        $error = "Username tidak boleh kosong.";
    } else {
        // Cek apakah username sudah ada (selain user saat ini)
        $cek_username = mysqli_query($koneksi, "SELECT * FROM tabel_users WHERE username = '$username' AND id_user != '$id_user'");
        if (mysqli_num_rows($cek_username) > 0) {
            $error = "Username tersebut sudah digunakan oleh akun lain.";
        } else {
            // Jika password diisi, lakukan validasi dan update password
            if (!empty($password_baru) || !empty($konfirmasi_password)) {
                if ($password_baru !== $konfirmasi_password) {
                    $error = "Konfirmasi password tidak cocok dengan password baru.";
                } elseif (strlen($password_baru) < 5) {
                    $error = "Password baru minimal harus 5 karakter.";
                } else {
                    // Update username dan password baru
                    $password_md5 = md5($password_baru);
                    $query_update = "UPDATE tabel_users SET username = '$username', password = '$password_md5' WHERE id_user = '$id_user'";
                    if (mysqli_query($koneksi, $query_update)) {
                        $_SESSION['username'] = $username; // Update session username
                        $sukses = "Username dan password berhasil diperbarui.";
                    } else {
                        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
                    }
                }
            } else {
                // Jika password tidak diisi, hanya update username
                $query_update = "UPDATE tabel_users SET username = '$username' WHERE id_user = '$id_user'";
                if (mysqli_query($koneksi, $query_update)) {
                    $_SESSION['username'] = $username; // Update session username
                    $sukses = "Username berhasil diperbarui.";
                } else {
                    $error = "Gagal memperbarui username: " . mysqli_error($koneksi);
                }
            }
        }
    }
    // Refresh data user setelah update
    if (empty($error)) {
        $query_user = mysqli_query($koneksi, "SELECT * FROM tabel_users WHERE id_user = '$id_user'");
        $user_data = mysqli_fetch_assoc($query_user);
    }
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Ubah Pengaturan Akun</h6>
    </div>
    <div class="card-body">
        <?php if ($error) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
        <?php endif; ?>
        <?php if ($sukses) : ?>
        <div class="alert alert-success" role="alert">
            <?= $sukses ?>
        </div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= htmlspecialchars($user_data['username']) ?>" required>
                    </div>
                    <hr>
                    <p class="text-muted">Kosongkan jika tidak ingin mengubah password.</p>
                    <div class="mb-3">
                        <label for="password_baru" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password_baru" name="password_baru">
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password">
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php
include '../dashboard/partials/footer.php';
?>