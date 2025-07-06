<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id_modul = $_GET['id'] ?? null;
$id_praktikum = $_GET['praktikum'] ?? null;

if (!$id_modul || !$id_praktikum) {
    die("ID tidak lengkap.");
}

// Ambil data modul
$stmt = $conn->prepare("SELECT * FROM modul WHERE id = ?");
$stmt->bind_param("i", $id_modul);
$stmt->execute();
$modul = $stmt->get_result()->fetch_assoc();

if (!$modul) {
    die("Modul tidak ditemukan.");
}

// Handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_modul = $_POST['nama_modul'];
    $file = $_FILES['file_materi'];
    $nama_file = $modul['file_materi'];

    if ($file && $file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
            die("Format file tidak valid.");
        }

        // Hapus file lama jika ada
        if ($nama_file && file_exists("../uploads/materi/$nama_file")) {
            unlink("../uploads/materi/$nama_file");
        }

        $nama_file = 'materi_' . time() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], "../uploads/materi/$nama_file");
    }

    $stmt = $conn->prepare("UPDATE modul SET nama_modul = ?, file_materi = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nama_modul, $nama_file, $id_modul);
    $stmt->execute();

    header("Location: kelola_modul.php?id=$id_praktikum");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Modul</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Edit Modul</h1>
        <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
            <label class="block mb-2">Nama Modul</label>
            <input type="text" name="nama_modul" value="<?= htmlspecialchars($modul['nama_modul']) ?>" class="w-full p-2 border rounded mb-3" required>

            <label class="block mb-2">Ganti File Materi (Opsional)</label>
            <input type="file" name="file_materi" class="w-full mb-3">

            <?php if ($modul['file_materi']) : ?>
                <p class="text-sm text-gray-600">File lama: <a href="../uploads/materi/<?= $modul['file_materi'] ?>" target="_blank" class="text-blue-600 underline"><?= $modul['file_materi'] ?></a></p>
            <?php endif; ?>

            <div class="flex justify-between mt-4">
                <a href="kelola_modul.php?id=<?= $id_praktikum ?>" class="text-gray-600 hover:underline">⬅ Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>