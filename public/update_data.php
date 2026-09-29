<?php
require 'auth.php';
requireLogin();
require 'firebase_config.php';

$id = $_GET['id'];
$laporanRef = $database->getReference("laporan/$id");
$laporan = $laporanRef->getValue();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $laporanRef->update([
        'nama_pelapor' => $_POST['nama_pelapor'],
        'lokasi'       => $_POST['lokasi'],
        'kategori'     => $_POST['kategori'],
        'prioritas'    => $_POST['prioritas'],
        'deskripsi'    => $_POST['deskripsi'],
        'status'       => $_POST['status'],
        'updated_at'   => date('c')
    ]);

    header('Location: view_data.php');
    exit;
}


$kategoriOptions  = ['AC', 'Proyektor', 'Lampu', 'Meja / Kursi', 'Toilet', 'Jaringan Wi-Fi', 'Komputer Lab', 'Lainnya'];
$prioritasOptions = ['Rendah', 'Sedang', 'Tinggi'];
$statusOptions    = ['Menunggu', 'Diproses', 'Selesai'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Edit Laporan</title>
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
            <a href="logout.php" class="btn btn-light fn-nav-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="fn-card p-4 p-md-5">
                <h5 class="fw-bold mb-4"><i class="bi bi-pencil-square text-primary"></i> Edit Laporan</h5>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fn-form-label">Nama Pelapor</label>
                            <input type="text" name="nama_pelapor" class="form-control"
                                   value="<?= htmlspecialchars($laporan['nama_pelapor']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fn-form-label">Lokasi Fasilitas</label>
                            <input type="text" name="lokasi" class="form-control"
                                   value="<?= htmlspecialchars($laporan['lokasi']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fn-form-label">Kategori Masalah</label>
                            <select name="kategori" class="form-select" required>
                                <?php foreach ($kategoriOptions as $opt): ?>
                                    <option <?= $laporan['kategori'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="fn-form-label">Tingkat Prioritas</label>
                            <select name="prioritas" class="form-select" required>
                                <?php foreach ($prioritasOptions as $opt): ?>
                                    <option <?= $laporan['prioritas'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="fn-form-label">Deskripsi Kerusakan</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($laporan['deskripsi']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="fn-form-label">
                                Status Laporan
                                <span class="badge-soft badge-diproses ms-1" style="font-size:0.68rem;">Ditangani teknisi/admin</span>
                            </label>
                            <select name="status" class="form-select" required>
                                <?php foreach ($statusOptions as $opt): ?>
                                    <option <?= $laporan['status'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="view_data.php" class="btn btn-fn-outline w-50">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-fn-primary w-50">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="fn-footer">FixNow, aplikasi pelaporan fasilitas berbasis PHP &amp; Firebase Realtime Database. Dibuat oleh Rensy Indra Gifani.</div>

</body>
</html>
