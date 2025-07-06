<?php
session_start();
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'mahasiswa') {
    die("Akses ditolak. Harap login sebagai mahasiswa.");
}

$id_mahasiswa = $_SESSION['id'];
$id_praktikum = $_POST['id_praktikum'];

// Cek apakah sudah terdaftar
$cek = $conn->prepare("SELECT * FROM pendaftaran_praktikum WHERE id_mahasiswa = ? AND id_praktikum = ?");
$cek->bind_param("ii", $id_mahasiswa, $id_praktikum);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Kamu sudah mendaftar pada praktikum ini.'); window.location.href='katalog_praktikum.php';</script>";
    exit;
}

// Tambahkan ke tabel pendaftaran
$stmt = $conn->prepare("INSERT INTO pendaftaran_praktikum (id_mahasiswa, id_praktikum) VALUES (?, ?)");
$stmt->bind_param("ii", $id_mahasiswa, $id_praktikum);

if ($stmt->execute()) {
    echo "<script>alert('Berhasil mendaftar praktikum!'); window.location.href='katalog_praktikum.php';</script>";
} else {
    echo "<script>alert('Gagal mendaftar.'); window.location.href='katalog_praktikum.php';</script>";
}
