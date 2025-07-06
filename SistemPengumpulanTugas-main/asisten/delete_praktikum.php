<?php
session_start();
require_once '../config.php';

// Cek role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID tidak valid.");
}

$stmt = $conn->prepare("DELETE FROM praktikum WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: daftar_praktikum.php");
exit;
