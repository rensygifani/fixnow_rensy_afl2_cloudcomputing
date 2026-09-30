<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// lokasi kredensial: di Render pakai Secret File, di lokal pakai folder src
$credPath = '/etc/secrets/firebase_credentials.json';
if (!is_readable($credPath)) {
    $credPath = __DIR__ . '/../src/firebase_credentials.json';
}

// koneksi ke Firebase pakai service account
$factory = (new Factory)
    ->withServiceAccount($credPath)
    ->withDatabaseUri('https://fixnow-cloud-computing-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();

// dipakai register.php dan login.php
$auth = $factory->createAuth();
?>
