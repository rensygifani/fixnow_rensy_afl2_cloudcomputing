<?php
require 'auth.php';
redirectIfLoggedIn();
require 'firebase_config.php';

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = 'Konfirmasi password tidak sama.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        try {
            $auth->createUserWithEmailAndPassword($email, $password);

            header('Location: login.php?registered=1');
            exit;
        } catch (Exception $e) {
            $error = authErrorMessage($e);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Register</title>
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
                <h5 class="fw-bold mb-1"><i class="bi bi-person-plus text-primary"></i> Buat Akun</h5>
                <p class="text-muted mb-4">Daftar dulu supaya bisa melaporkan kerusakan fasilitas.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="fn-form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="cth: budi@email.com"
                               value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="fn-form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                    </div>
                    <div class="mb-4">
                        <label class="fn-form-label">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" minlength="6" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-fn-primary">
                            <i class="bi bi-person-check"></i>&nbsp; Daftar
                        </button>
                    </div>
                </form>

                <?php include 'social_buttons.php'; ?>

                <p class="text-center text-muted mt-4 mb-0">Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

<div class="fn-footer">FixNow, aplikasi pelaporan fasilitas berbasis PHP &amp; Firebase Realtime Database. Dibuat oleh Rensy Indra Gifani.</div>

</body>
</html>
