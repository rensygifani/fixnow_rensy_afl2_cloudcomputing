<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return !empty($_SESSION['uid']);
}

// buat halaman yang wajib login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// kalau udah login, langsung ke beranda
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

// biar pesan error firebase gampang dibaca
function authErrorMessage($e) {
    $msg = $e->getMessage();
    $map = [
        'INVALID_PASSWORD'            => 'Password salah.',
        'EMAIL_NOT_FOUND'             => 'Email belum terdaftar.',
        'INVALID_LOGIN_CREDENTIALS'   => 'Email atau password salah.',
        'USER_DISABLED'               => 'Akun ini dinonaktifkan.',
        'EMAIL_EXISTS'                => 'Email sudah terdaftar, silakan login.',
        'WEAK_PASSWORD'               => 'Password terlalu lemah (minimal 6 karakter).',
        'INVALID_EMAIL'               => 'Format email tidak valid.',
        'TOO_MANY_ATTEMPTS_TRY_LATER' => 'Terlalu banyak percobaan, coba lagi nanti.',
    ];
    foreach ($map as $code => $text) {
        if (stripos($msg, $code) !== false) {
            return $text;
        }
    }
    return 'Terjadi kesalahan: ' . $msg;
}
?>
