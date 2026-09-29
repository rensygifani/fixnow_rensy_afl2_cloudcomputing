# FixNow

Aplikasi CRUD sederhana buat laporin kerusakan fasilitas (AC, proyektor, lampu, toilet, wifi, dll), pakai PHP dan Firebase Realtime Database (kreait/firebase-php).

Dibuat buat tugas AFL 2 mata kuliah Cloud Computing, implementasi BaaS pakai Firebase. Di Sesi 9 ditambah fitur register/login pakai Firebase Authentication, termasuk login lewat Google dan GitHub.

Dibuat oleh Rensy Indra Gifani.

## Data yang disimpan

Semua laporan disimpan di node `laporan`, isinya:
1. `nama_pelapor`
2. `lokasi`
3. `kategori` (AC, Proyektor, Lampu, dll)
4. `prioritas` (Rendah / Sedang / Tinggi)
5. `deskripsi`
6. `status` (Menunggu / Diproses / Selesai, default "Menunggu" saat laporan baru dibuat)

## Struktur file

```
fixnow/
├── composer.json
├── dockerfile
├── src/
│   └── firebase_credentials.json   <- ganti sama punya sendiri
└── public/
    ├── register.php        daftar akun baru (Firebase Authentication)
    ├── login.php           login email + password
    ├── logout.php          hapus session, balik ke login
    ├── social_login.php    verifikasi token Google/GitHub
    ├── social_buttons.php  tombol + JS login Google/GitHub
    ├── firebase_web_config.php  config firebase buat web
    ├── auth.php            helper session
    ├── index.php           form buat laporan baru (Create)
    ├── insert.php          simpan data baru ke Firebase
    ├── view_data.php       tampilkan semua laporan (Read)
    ├── update_data.php     form edit + ubah status (Update)
    ├── delete_data.php     hapus laporan (Delete)
    └── firebase_config.php koneksi ke Firebase Admin SDK
```

## Cara setup

1. Buat project Firebase, aktifkan Realtime Database (mode test).
2. Project settings > Service accounts > Generate new private key, taruh file JSON-nya di `src/firebase_credentials.json`. File ini berisi kredensial admin, jangan di-commit ke GitHub publik (sudah ada di `.gitignore`).
3. Sesuaikan URL database di `public/firebase_config.php`.
4. Jalankan `composer require kreait/firebase-php` dari root folder buat generate `vendor/`.
5. Jalankan:
   ```
   cd public
   php -S localhost:8000
   ```
   atau pakai Docker: `docker build -t fixnow-php .` lalu `docker run -p 8080:80 fixnow-php`.

## Firebase Authentication (Sesi 9)

1. Firebase Console > Authentication > Sign-in method, aktifkan Email/Password.
2. Buka `register.php`, daftar pakai email + password (minimal 6 karakter), cek akunnya muncul di Firebase Console > Authentication.
3. Login lewat `login.php`. Coba juga password yang salah, harus muncul pesan error.
4. Klik Logout, coba buka `view_data.php` langsung, harus balik ke login.

### Login Google & GitHub

Tombol di browser buka popup Firebase, dapat ID token, token dikirim ke `social_login.php`, diverifikasi di server, lalu bikin session sama seperti login email.

1. Aktifkan Google di Sign-in method Firebase.
2. Untuk GitHub: buat OAuth App di GitHub (Settings > Developer settings > OAuth Apps), isi callback URL dari Firebase, tempel Client ID & Secret dari GitHub ke Firebase.
3. Project settings > Your apps, tambah Web app, salin config ke `public/firebase_web_config.php`.
4. Authentication > Settings > Authorized domains, pastikan `localhost` ada.

Selama `firebase_web_config.php` masih placeholder, tombol Google/GitHub tidak tampil dan login email tetap jalan normal.

## Catatan

- Sejak Sesi 9, semua halaman laporan wajib login. Belum ada role, pelapor dan yang menangani laporan pakai form yang sama.
- Tampilan pakai Bootstrap 5 + Bootstrap Icons dari CDN.
