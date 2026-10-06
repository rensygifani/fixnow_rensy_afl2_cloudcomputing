<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return !empty($_SESSION['uid']);
}

// membuat halaman yang wajib login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// kalau sudah login, langsung ke beranda
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

// alamat aplikasi, dipakai buat link di email verifikasi
// kalau mau bisa diisi lewat env APP_URL di Render
function appBaseUrl() {
    $env = getenv('APP_URL');
    if ($env) {
        return rtrim($env, '/');
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $https ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    return $scheme . '://' . $host . $dir;
}

// kirim email verifikasi, link-nya mengarah ke verify.php
function sendVerificationEmail($auth, $email) {
    $settings = [
        'continueUrl'     => appBaseUrl() . '/verify.php?email=' . urlencode($email),
        'handleCodeInApp' => false,
    ];
    $auth->sendEmailVerificationLink($email, $settings);
}

// agar pesan error firebase gampang dibaca
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
