<?php
require 'firebase_config.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namaPelapor = $_POST['nama_pelapor'];
    $lokasi      = $_POST['lokasi'];
    $kategori    = $_POST['kategori'];
    $prioritas   = $_POST['prioritas'];
    $deskripsi   = $_POST['deskripsi'];

    // status default selalu "Menunggu", diubah nanti lewat update_data.php
    $database->getReference('laporan')->push([
        'nama_pelapor' => $namaPelapor,
        'lokasi'       => $lokasi,
        'kategori'     => $kategori,
        'prioritas'    => $prioritas,
        'deskripsi'    => $deskripsi,
        'status'       => 'Menunggu',
        'created_at'   => date('c')
    ]);

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Laporan Terkirim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="fn-navbar">
    <div class="container">
        <a href="index.php" class="fn-brand">
            <span class="fn-brand-icon"><i class="bi bi-tools"></i></span>
            FixNow
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="fn-card p-5 text-center">
                <?php if ($success): ?>
                    <div class="fn-result-icon fn-result-success">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h4 class="fw-bold">Laporan Berhasil Dikirim!</h4>
                    <p class="text-muted">Laporan kamu sudah tersimpan dengan status
                        <span class="badge-soft badge-menunggu">Menunggu</span>
                    </p>
                <?php else: ?>
                    <div class="fn-result-icon fn-result-error">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <h4 class="fw-bold">Gagal Mengirim Laporan</h4>
                    <p class="text-muted">Terjadi kesalahan, silakan coba lagi.</p>
                <?php endif; ?>

                <div class="d-flex gap-2 justify-content-center mt-4">
                    <a href="index.php" class="btn btn-fn-outline"><i class="bi bi-plus-lg"></i> Laporan Lain</a>
                    <a href="view_data.php" class="btn btn-fn-primary"><i class="bi bi-list-check"></i> Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fn-footer">FixNow, aplikasi pelaporan fasilitas berbasis PHP &amp; Firebase Realtime Database. Dibuat oleh Rensy Indra Gifani.</div>

</body>
</html>
