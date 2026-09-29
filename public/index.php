<?php
require 'auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Buat Laporan</title>
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
            <a href="view_data.php" class="btn btn-light fn-nav-btn">
            <i class="bi bi-list-check"></i> Lihat Laporan
        </a>
            <a href="logout.php" class="btn btn-light fn-nav-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="fn-hero">
        <span class="badge badge-soft badge-diproses mb-2"><i class="bi bi-lightning-charge-fill"></i> Pusat Pelaporan Fasilitas</span>
        <h4>Temukan masalah fasilitas? Laporkan sekarang. 🛠️</h4>
        <p class="text-muted mb-0">Isi form di bawah, laporan langsung tersimpan ke Firebase Realtime Database dengan status <b>Menunggu</b>.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="fn-card p-4 p-md-5">
                <h5 class="fw-bold mb-4"><i class="bi bi-pencil-square text-primary"></i> Form Laporan Baru</h5>

                <form action="insert.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fn-form-label">Nama Pelapor</label>
                            <input type="text" name="nama_pelapor" class="form-control" placeholder="cth: Budi Santoso" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fn-form-label">Lokasi Fasilitas</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="cth: Ruang Kelas 301" required>
                        </div>

                        <div class="col-md-6">
                            <label class="fn-form-label">Kategori Masalah</label>
                            <select name="kategori" class="form-select" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                <option>AC</option>
                                <option>Proyektor</option>
                                <option>Lampu</option>
                                <option>Meja / Kursi</option>
                                <option>Toilet</option>
                                <option>Jaringan Wi-Fi</option>
                                <option>Komputer Lab</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fn-form-label">Tingkat Prioritas</label>
                            <select name="prioritas" class="form-select" required>
                                <option value="" disabled selected>Pilih prioritas</option>
                                <option>Rendah</option>
                                <option>Sedang</option>
                                <option>Tinggi</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="fn-form-label">Deskripsi Kerusakan</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan kondisi yang ditemukan..." required></textarea>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-fn-primary">
                            <i class="bi bi-send-check"></i>&nbsp; Kirim Laporan
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
