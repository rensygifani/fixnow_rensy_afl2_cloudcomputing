<?php
require 'auth.php';

$_SESSION = [];
session_destroy();

header('Location: login.php?logout=1');
exit;
?>
