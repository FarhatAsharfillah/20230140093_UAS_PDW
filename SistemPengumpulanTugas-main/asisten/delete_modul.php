<?php
session_start();
require_once '../config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id_modul = $_GET['id'] ?? null;
$id_praktikum = $_GET['praktikum'] ?? null;

if (!$id_modul || !$id_praktikum) {
    die("ID tidak lengkap.");
}

// Ambil nama file jika ada
$stmt = $conn->prepare("SELECT file_materi FROM modul WHERE id = ?");
$stmt->bind_param("i", $id_modul);
$stmt->execute();
$stmt->bind_result($file_materi);
$stmt->fetch();
$stmt->close();

// Hapus file fisik jika ada
if ($file_materi && file_exists("../uploads/materi/$file_materi")) {
    unlink("../uploads/materi/$file_materi");
}

// Hapus dari DB
$stmt = $conn->prepare("DELETE FROM modul WHERE id = ?");
$stmt->bind_param("i", $id_modul);
$stmt->execute();

header("Location: kelola_modul.php?id=$id_praktikum");
exit;
