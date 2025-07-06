<?php
session_start();
            require_once '../config.php';
            require_once 'templates/header_mahasiswa.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    die("Akses ditolak.");
}

$id_mahasiswa = $_SESSION['user_id'];
$id_praktikum = $_GET['id'] ?? null;

if (!$id_praktikum) {
    die("ID praktikum tidak valid.");
}

// Ambil nama praktikum
$praktikum = $conn->query("SELECT * FROM praktikum WHERE id = $id_praktikum")->fetch_assoc();

// Ambil daftar modul untuk praktikum ini
$sql = "SELECT m.*, l.file_laporan, l.nilai, l.feedback
        FROM modul m
        LEFT JOIN laporan_mahasiswa l 
        ON m.id = l.id_modul AND l.id_mahasiswa = $id_mahasiswa
        WHERE m.id_praktikum = $id_praktikum";

$modul_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Detail Tugas</h1>
        <div class="space-y-6">
            <?php while ($modul = $modul_result->fetch_assoc()): ?>
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-lg font-semibold"><?= htmlspecialchars($modul['nama_modul']) ?></h2>

                    <!-- Upload laporan -->
                    <?php if (!$modul['file_laporan']): ?>
                        <form action="upload_laporan.php" method="post" enctype="multipart/form-data" class="mt-2">
                            <input type="hidden" name="id_modul" value="<?= $modul['id'] ?>">
                            <input type="file" name="file_laporan" required>
                            <button type="submit" class="ml-2 px-3 py-1 bg-green-500 text-white rounded">Upload</button>
                        </form>
                    <?php else: ?>
                        <p class="text-sm mt-2 text-gray-700">📤 Laporan telah diunggah: <?= htmlspecialchars($modul['file_laporan']) ?></p>
                    <?php endif; ?>

                    <!-- Nilai dan feedback -->
                    <?php if ($modul['nilai'] !== null): ?>
                        <p class="text-sm mt-2 text-gray-800">📊 Nilai: <strong><?= $modul['nilai'] ?></strong></p>
                        <p class="text-sm italic text-gray-600">💬 Feedback: <?= htmlspecialchars($modul['feedback']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-6 text-center">
            <a href="praktikum_saya.php" class="text-blue-600 hover:underline">⬅ Kembali</a>
        </div>
    </div>
</body>

</html>