# FixNow

Aplikasi CRUD sederhana buat laporin kerusakan fasilitas (AC, proyektor, lampu, toilet, wifi, dll), pakai PHP dan Firebase Realtime Database (kreait/firebase-php).

Dibuat buat tugas AFL 2 mata kuliah Cloud Computing, implementasi BaaS pakai Firebase.

Dibuat oleh Rensy Indra Gifani.

## Data yang disimpan

Semua laporan disimpan di node `laporan`, isinya:

1. `nama_pelapor` - nama yang lapor
2. `lokasi` - lokasi fasilitas yang bermasalah
3. `kategori` - AC, Proyektor, Lampu, dll
4. `prioritas` - Rendah / Sedang / Tinggi
5. `deskripsi` - penjelasan kerusakannya
6. `status` - Menunggu / Diproses / Selesai (default "Menunggu" pas baru dibuat)

plus `created_at` dan `updated_at` yang keisi otomatis.

## Struktur file

```
fixnow/
├── composer.json
├── dockerfile             (opsional, buat run via Docker)
├── src/
│   └── firebase_credentials.json   <- ganti sama punya kalian sendiri
└── public/
    ├── index.php           form buat laporan baru (Create)
    ├── insert.php          proses simpan ke Firebase
    ├── view_data.php       tampilin semua laporan (Read)
    ├── update_data.php     edit data + ubah status (Update)
    ├── delete_data.php     hapus laporan (Delete)
    └── firebase_config.php koneksi ke Firebase
```

## Testing CRUD

1. Buka halaman utama, isi form, klik Kirim Laporan (Create)
2. Klik Lihat Laporan, cek data barusan muncul (Read)
3. Klik ikon edit di salah satu baris, ubah statusnya, simpan (Update)
4. Klik ikon hapus (Delete)
5. Cek juga di Firebase Console > Realtime Database, datanya harus ikut berubah

## Catatan

- Belum ada login/role, pelapor sama admin pakai form yang sama
- Status baru selalu "Menunggu" dulu, baru bisa diubah lewat halaman edit
- Tampilan pakai Bootstrap 5 + Bootstrap Icons dari CDN
