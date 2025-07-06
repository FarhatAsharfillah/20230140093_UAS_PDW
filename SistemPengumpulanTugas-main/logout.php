<?php
session_start();
$_SESSION = array();
session_destroy();

// Opsi 1 - path relatif:
header("Location: login.php");

// Opsi 2 - path absolut (lebih aman kalau dipindahkan folder):
// header("Location: /SistemPengumpulanTugas/SistemPengumpulanTugas-main/login.php");
exit;
