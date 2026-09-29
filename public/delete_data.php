<?php
require 'auth.php';
requireLogin();
require 'firebase_config.php';

$id = $_GET['id'];
$database->getReference("laporan/$id")->remove();

header('Location: view_data.php');
exit;
?>
