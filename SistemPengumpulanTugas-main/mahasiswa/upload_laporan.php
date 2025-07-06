<?php
session_start();
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    die("Akses ditolak.");
}

$id_mahasiswa = $_SESSION['user_id'];
$id_modul = $_POST['id_modul'] ?? null;

// Validasi file upload
if (!isset($_FILES['file_laporan']) || $_FILES['file_laporan']['error'] !== UPLOAD_ERR_OK) {
    die("Gagal mengunggah file.");
}

$file = $_FILES['file_laporan'];
$ekstensi_diperbolehkan = ['pdf', 'doc', 'docx'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $ekstensi_diperbolehkan)) {
    die("Hanya file PDF, DOC, atau DOCX yang diperbolehkan.");
}

// Buat nama file unik
$nama_file_baru = 'laporan_' . $id_mahasiswa . '_' . $id_modul . '_' . time() . '.' . $ext;
$tujuan = '../uploads/laporan/' . $nama_file_baru;

if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
    die("Gagal menyimpan file.");
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO laporan_mahasiswa (id_mahasiswa, id_modul, file_laporan) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE file_laporan = VALUES(file_laporan), tanggal_upload = CURRENT_TIMESTAMP");
$stmt->bind_param("iis", $id_mahasiswa, $id_modul, $nama_file_baru);
$stmt->execute();

if ($stmt->affected_rows >= 1) {
    echo "<script>alert('Laporan berhasil diunggah!'); window.history.back();</script>";
} else {
    echo "<script>alert('Gagal menyimpan laporan.'); window.history.back();</script>";
}
