<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// koneksi ke Firebase pakai service account
$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/../src/firebase_credentials.json')
    ->withDatabaseUri('https://fixnow-cloud-computing-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();

// dipakai register.php dan login.php
$auth = $factory->createAuth();
?>
