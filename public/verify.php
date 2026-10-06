<?php
// halaman tujuan setelah user klik link verifikasi di email
require 'auth.php';
require 'firebase_config.php';

$email = trim($_GET['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?verifyerror=1');
    exit;
}

try {
    $user = $auth->getUserByEmail($email);

    if ($user->emailVerified) {
        // update status di database
        try {
            $database->getReference('users/' . md5(strtolower($email)) . '/is_verified')->set(true);
        } catch (Exception $e) {
            // kalau gagal update, user tetap bisa login
        }
        header('Location: login.php?verified=1');
    } else {
        header('Location: login.php?notverified=1');
    }
} catch (Exception $e) {
    header('Location: login.php?verifyerror=1');
}
exit;
