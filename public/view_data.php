<?php
require 'auth.php';
requireLogin();
require 'firebase_config.php';

// Mengambil semua data laporan dari Firebase
$laporanList = $database->getReference('laporan')->getValue();

// hitung jumlah per status buat kartu statistik
$total = 0; $menunggu = 0; $diproses = 0; $selesai = 0;
if ($laporanList) {
    $total = count($laporanList);
    foreach ($laporanList as $l) {
        if ($l['status'] === 'Menunggu') $menunggu++;
        if ($l['status'] === 'Diproses') $diproses++;
        if ($l['status'] === 'Selesai')  $selesai++;
    }
}

// warna badge sesuai status/prioritas
function statusBadgeClass($status) {
    switch ($status) {
        case 'Menunggu': return 'badge-menunggu';
        case 'Diproses': return 'badge-diproses';
        case 'Selesai':  return 'badge-selesai';
        default: return 'bg-secondary';
    }
}

function prioritasBadgeClass($prioritas) {
    switch ($prioritas) {
        case 'Tinggi': return 'badge-tinggi';
        case 'Sedang': return 'badge-sedang';
        case 'Rendah': return 'badge-rendah';
        default: return 'bg-secondary';
    }
}

function statusIcon($status) {
    switch ($status) {
        case 'Menunggu': return 'bi-hourglass-split';
        case 'Diproses': return 'bi-arrow-repeat';
        case 'Selesai':  return 'bi-check-circle-fill';
        default: return 'bi-question-circle';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Daftar Laporan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="fn-navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index.php" class="fn-brand">
            <span class="fn-brand-icon"><i class="bi bi-tools"></i></span>
            FixNow
        </a>
        <div class="d-flex align-items-center gap-2">
            <span class="fn-user d-none d-md-inline"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['email']) ?></span>
            <a href="index.php" class="btn btn-light fn-nav-btn">
            <i class="bi bi-plus-lg"></i> Laporan Baru
        </a>
            <a href="logout.php" class="btn btn-light fn-nav-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container py-4">

    <!-- Kartu Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="fn-stat fn-stat-total">
                <i class="bi bi-clipboard-data"></i>
                <div class="fn-stat-number"><?= $total ?></div>
                <div class="fn-stat-label">Total Laporan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="fn-stat fn-stat-menunggu">
                <i class="bi bi-hourglass-split"></i>
                <div class="fn-stat-number"><?= $menunggu ?></div>
                <div class="fn-stat-label">Menunggu</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="fn-stat fn-stat-diproses">
                <i class="bi bi-arrow-repeat"></i>
                <div class="fn-stat-number"><?= $diproses ?></div>
                <div class="fn-stat-label">Diproses</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="fn-stat fn-stat-selesai">
                <i class="bi bi-check-circle"></i>
                <div class="fn-stat-number"><?= $selesai ?></div>
                <div class="fn-stat-label">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="fn-card p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-check text-primary"></i> Daftar Laporan Fasilitas</h5>
        </div>

        <div class="table-responsive">
            <table class="table fn-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pelapor</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($laporanList): ?>
                        <?php foreach (array_reverse($laporanList, true) as $id => $laporan): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($laporan['nama_pelapor']) ?></td>
                                <td><?= htmlspecialchars($laporan['lokasi']) ?></td>
                                <td><?= htmlspecialchars($laporan['kategori']) ?></td>
                                <td><span class="badge-soft <?= prioritasBadgeClass($laporan['prioritas']) ?>"><?= htmlspecialchars($laporan['prioritas']) ?></span></td>
                                <td class="text-muted" style="max-width: 220px;"><?= htmlspecialchars($laporan['deskripsi']) ?></td>
                                <td>
                                    <span class="badge-soft <?= statusBadgeClass($laporan['status']) ?>">
                                        <i class="bi <?= statusIcon($laporan['status']) ?>"></i>
                                        <?= htmlspecialchars($laporan['status']) ?>
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="update_data.php?id=<?= urlencode($id) ?>" class="btn btn-sm btn-fn-outline">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete_data.php?id=<?= urlencode($id) ?>"
                                       class="btn btn-sm btn-fn-danger-soft"
                                       onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="fn-empty">
                                    <i class="bi bi-inbox"></i>
                                    Belum ada laporan. Yuk buat laporan pertama!
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="fn-footer">FixNow, aplikasi pelaporan fasilitas berbasis PHP &amp; Firebase Realtime Database. Dibuat oleh Rensy Indra Gifani.</div>

</body>
</html>
