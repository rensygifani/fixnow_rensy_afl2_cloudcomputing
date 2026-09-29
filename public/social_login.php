<?php
// verifikasi ID token dari login Google/GitHub dan buat session seperti login email
require 'auth.php';
require 'firebase_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metode tidak diizinkan.']);
    exit;
}

$body    = json_decode(file_get_contents('php://input'), true);
$idToken = is_array($body) ? ($body['idToken'] ?? '') : '';

if (!is_string($idToken) || $idToken === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Token tidak ditemukan.']);
    exit;
}

try {
    // leeway 300 detik jaga-jaga kalau jam komputer beda dikit
    $verified = $auth->verifyIdToken($idToken, false, 300);
    $claims   = $verified->claims();

    $uid = $claims->get('sub');
    if (!$uid) {
        throw new Exception('UID tidak ditemukan.');
    }

    // github kadang tidak kasih email, jadi ada cadangan agar navbar gak kosong
    $label = $claims->get('email', null) ?: $claims->get('name', null) ?: ('user-' . substr($uid, 0, 6));

    session_regenerate_id(true);
    $_SESSION['uid']   = $uid;
    $_SESSION['email'] = $label;

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Verifikasi token gagal: ' . $e->getMessage()]);
}
