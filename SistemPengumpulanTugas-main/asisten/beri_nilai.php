<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id_laporan = $_POST['id_laporan'];
$nilai = $_POST['nilai'];

if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
    die("Nilai tidak valid.");
}

$stmt = $conn->prepare("UPDATE laporan_mahasiswa SET nilai = ? WHERE id = ?");
$stmt->bind_param("ii", $nilai, $id_laporan);
$stmt->execute();

header("Location: laporan_masuk.php");
exit();
