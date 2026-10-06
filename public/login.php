<?php
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;

require 'auth.php';
redirectIfLoggedIn();
require 'firebase_config.php';

$error = '';
$info  = '';
$email = '';
$showResend = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['action'] ?? '') === 'resend') {
    // kirim ulang link verifikasi
    $pending = $_SESSION['pending_verify_email'] ?? '';
    $showResend = ($pending !== '');
    if ($pending === '') {
        $error = 'Silakan login terlebih dahulu, lalu kirim ulang link verifikasi.';
    } else {
        try {
            sendVerificationEmail($auth, $pending);
            $info = 'Link verifikasi baru sudah dikirim ke ' . $pending . '. Cek inbox atau folder spam.';
        } catch (Exception $e) {
            $error = 'Gagal mengirim email verifikasi: ' . authErrorMessage($e);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        // cek akun dulu biar error akun tidak ada dan password salah bisa dibedakan
        try {
            $user = $auth->getUserByEmail($email);
        } catch (UserNotFound $e) {
            throw new Exception('ACCOUNT_NOT_FOUND');
        }

        // cek password
        try {
            $result = $auth->signInWithEmailAndPassword($email, $password);
        } catch (InvalidPassword $e) {
            throw new Exception('WRONG_PASSWORD');
        } catch (Exception $e) {
            // firebase terbaru kadang kasih INVALID_LOGIN_CREDENTIALS buat password salah
            if (stripos($e->getMessage(), 'INVALID_PASSWORD') !== false
                || stripos($e->getMessage(), 'INVALID_LOGIN_CREDENTIALS') !== false) {
                throw new Exception('WRONG_PASSWORD');
            }
            throw $e;
        }

        $uid = $result->firebaseUserId();
        if (!$uid) {
            throw new Exception('UID user tidak ditemukan.');
        }

        // password benar, tapi email harus sudah diverifikasi
        $user = $auth->getUser($uid);
        if (!$user->emailVerified) {
            $_SESSION['pending_verify_email'] = $email;
            $showResend = true;
            throw new Exception('EMAIL_NOT_VERIFIED');
        }

        unset($_SESSION['pending_verify_email']);
        session_regenerate_id(true);
        $_SESSION['uid']   = $uid;
        $_SESSION['email'] = $email;

        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        switch ($e->getMessage()) {
            case 'ACCOUNT_NOT_FOUND':
                $error = 'Akun tidak ditemukan. Periksa email kamu atau daftar terlebih dahulu.';
                break;
            case 'WRONG_PASSWORD':
                $error = 'Password salah. Silakan coba lagi.';
                break;
            case 'EMAIL_NOT_VERIFIED':
                $error = 'Email belum diverifikasi. Silakan cek email kamu dan klik link verifikasi terlebih dahulu.';
                break;
            default:
                $error = authErrorMessage($e);
        }
    }
} elseif (!empty($_SESSION['pending_verify_email']) && isset($_GET['registered'])) {
    $showResend = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="fn-navbar">
    <div class="container">
        <a href="login.php" class="fn-brand">
            <span class="fn-brand-icon"><i class="bi bi-tools"></i></span>
            FixNow
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="fn-card p-4 p-md-5">
                <h5 class="fw-bold mb-1"><i class="bi bi-box-arrow-in-right text-primary"></i> Login</h5>
                <p class="text-muted mb-4">Masuk untuk membuat dan mengelola laporan fasilitas.</p>

                <?php if (isset($_GET['registered']) && isset($_GET['mailfail'])): ?>
                    <div class="alert alert-warning">Registrasi berhasil, tetapi email verifikasi gagal terkirim. Klik "Kirim ulang link verifikasi" di bawah.</div>
                <?php elseif (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">Registrasi berhasil. Link verifikasi sudah dikirim ke email kamu, klik dulu sebelum login.</div>
                <?php endif; ?>
                <?php if (isset($_GET['verified'])): ?>
                    <div class="alert alert-success">Email berhasil diverifikasi. Silakan login.</div>
                <?php endif; ?>
                <?php if (isset($_GET['notverified'])): ?>
                    <div class="alert alert-warning">Email belum terverifikasi. Silakan klik link di email kamu.</div>
                <?php endif; ?>
                <?php if (isset($_GET['verifyerror'])): ?>
                    <div class="alert alert-danger">Link verifikasi tidak valid. Silakan daftar ulang atau login untuk kirim ulang link.</div>
                <?php endif; ?>
                <?php if ($info): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($info) ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['logout'])): ?>
                    <div class="alert alert-info">Kamu sudah logout.</div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="fn-form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="cth: budi@email.com"
                               value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="fn-form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-fn-primary">
                            <i class="bi bi-box-arrow-in-right"></i>&nbsp; Login
                        </button>
                    </div>
                </form>

                <?php if ($showResend): ?>
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="action" value="resend">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-envelope"></i>&nbsp; Kirim ulang link verifikasi
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <?php include 'social_buttons.php'; ?>

                <p class="text-center text-muted mt-4 mb-0">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</div>

<div class="fn-footer">FixNow, aplikasi pelaporan fasilitas berbasis PHP &amp; Firebase Realtime Database. Dibuat oleh Rensy Indra Gifani.</div>


</body>
</html>
