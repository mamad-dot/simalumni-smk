<?php
$_GET['page'] = 'Dashboard';
// Path sudah diperbaiki dari '_partials' menjadi 'partials'
include 'partials/header.php';

// Query untuk data statistik
$total_alumni = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tabel_alumni"));
$alumni_bekerja = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tabel_alumni WHERE status='Bekerja'"));
$alumni_kuliah = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tabel_alumni WHERE status='Kuliah'"));
$alumni_wirausaha = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tabel_alumni WHERE status='Wirausaha'"));

// Query untuk grafik status alumni (Bar Chart)
$status_labels = ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja'];
$status_data = [];
foreach ($status_labels as $status) {
    $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_alumni WHERE status='$status'");
    $row = mysqli_fetch_assoc($result);
    $status_data[] = $row['total'];
}

// Query untuk grafik jurusan (Pie Chart)
$jurusan_result = mysqli_query($koneksi, "SELECT jurusan, COUNT(*) as total FROM tabel_alumni GROUP BY jurusan");
$jurusan_labels = [];
$jurusan_data = [];
while ($row = mysqli_fetch_assoc($jurusan_result)) {
    $jurusan_labels[] = $row['jurusan'];
    $jurusan_data[] = $row['total'];
}
?>

<h1 class="mb-4">Dashboard</h1>

<!-- Card Statistik -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Alumni</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_alumni) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Alumni Bekerja</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($alumni_bekerja) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-briefcase-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Alumni Kuliah</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($alumni_kuliah) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-mortarboard-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Alumni Wirausaha</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($alumni_wirausaha) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-shop fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Status Alumni</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rekap Alumni Berdasarkan Jurusan</h6>
            </div>
            <div class="card-body">
                <canvas id="jurusanChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
.card .border-left-primary {
    border-left: .25rem solid #4e73df !important;
}

.card .border-left-success {
    border-left: .25rem solid #1cc88a !important;
}

.card .border-left-info {
    border-left: .25rem solid #36b9cc !important;
}

.card .border-left-warning {
    border-left: .25rem solid #f6c23e !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}
</style>

<script>
// Bar Chart - Status Alumni
var ctxStatus = document.getElementById('statusChart').getContext('2d');
var statusChart = new Chart(ctxStatus, {
    type: 'bar',
    data: {
        labels: <?= json_encode($status_labels) ?>,
        datasets: [{
            label: 'Jumlah Alumni',
            data: <?= json_encode($status_data) ?>,
            backgroundColor: [
                'rgba(78, 115, 223, 0.8)',
                'rgba(246, 194, 62, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(134, 137, 152, 0.8)'
            ],
            borderColor: [
                'rgba(78, 115, 223, 1)',
                'rgba(246, 194, 62, 1)',
                'rgba(54, 185, 204, 1)',
                'rgba(134, 137, 152, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Pie Chart - Jurusan
var ctxJurusan = document.getElementById('jurusanChart').getContext('2d');
var jurusanChart = new Chart(ctxJurusan, {
    type: 'pie',
    data: {
        labels: <?= json_encode($jurusan_labels) ?>,
        datasets: [{
            data: <?= json_encode($jurusan_data) ?>,
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#858796'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed !== null) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1) + '%';
                            label += context.raw + ' (' + percentage + ')';
                        }
                        return label;
                    }
                }
            }
        }
    },
});
</script>

<?php 
// Path sudah diperbaiki dari '_partials' menjadi 'partials'
include 'partials/footer.php'; 
?>