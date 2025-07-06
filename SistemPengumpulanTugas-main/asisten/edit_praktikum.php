<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

// Cek role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID praktikum tidak ditemukan.");
}

// Tangani POST (update data)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $semester = $_POST['semester'];
    $tahun = $_POST['tahun_ajaran'];

    $stmt = $conn->prepare("UPDATE praktikum SET nama=?, deskripsi=?, semester=?, tahun_ajaran=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama, $deskripsi, $semester, $tahun, $id);
    $stmt->execute();

    header("Location: daftar_praktikum.php");
    exit;
}

// Ambil data lama untuk form
$stmt = $conn->prepare("SELECT * FROM praktikum WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$praktikum = $result->fetch_assoc();

if (!$praktikum) {
    die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Edit Praktikum</h1>
        <form method="POST" class="bg-white p-4 rounded shadow">
            <label class="block mb-2">Nama Praktikum</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($praktikum['nama']) ?>" class="w-full p-2 border rounded mb-3" required>

            <label class="block mb-2">Deskripsi</label>
            <textarea name="deskripsi" class="w-full p-2 border rounded mb-3"><?= htmlspecialchars($praktikum['deskripsi']) ?></textarea>

            <label class="block mb-2">Semester</label>
            <input type="text" name="semester" value="<?= $praktikum['semester'] ?>" class="w-full p-2 border rounded mb-3" required>

            <label class="block mb-2">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" value="<?= $praktikum['tahun_ajaran'] ?>" class="w-full p-2 border rounded mb-3" required>

            <div class="flex justify-between">
                <a href="daftar_praktikum.php" class="text-gray-600 hover:underline">⬅ Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>

</html>