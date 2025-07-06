<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id_laporan = $_GET['id'] ?? null;

if (!$id_laporan) {
    die("ID laporan tidak ditemukan.");
}

// Ambil data laporan
$sql = "
SELECT l.*, u.nama AS nama_mahasiswa, p.nama AS nama_praktikum, m.nama_modul
FROM laporan_mahasiswa l
JOIN users u ON l.id_mahasiswa = u.id
JOIN modul m ON l.id_modul = m.id
JOIN praktikum p ON m.id_praktikum = p.id
WHERE l.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_laporan);
$stmt->execute();
$laporan = $stmt->get_result()->fetch_assoc();

if (!$laporan) {
    die("Data laporan tidak ditemukan.");
}

// Handle POST (penilaian)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = $_POST['nilai'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("UPDATE laporan_mahasiswa SET nilai = ?, feedback = ? WHERE id = ?");
    $stmt->bind_param("isi", $nilai, $feedback, $id_laporan);
    $stmt->execute();

    header("Location: laporan_masuk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Penilaian Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Nilai Laporan</h1>
        <div class="bg-white p-4 rounded shadow">
            <p class="mb-2"><strong>Mahasiswa:</strong> <?= htmlspecialchars($laporan['nama_mahasiswa']) ?></p>
            <p class="mb-2"><strong>Praktikum:</strong> <?= htmlspecialchars($laporan['nama_praktikum']) ?></p>
            <p class="mb-2"><strong>Modul:</strong> <?= htmlspecialchars($laporan['nama_modul']) ?></p>
            <p class="mb-2"><strong>File:</strong>
                <a href="../uploads/laporan/<?= $laporan['file_laporan'] ?>" target="_blank" class="text-blue-600 underline">
                    Unduh Laporan
                </a>
            </p>

            <form method="POST" class="mt-4 space-y-4">
                <div>
                    <label class="block font-semibold">Nilai (0–100)</label>
                    <input type="number" name="nilai" value="<?= $laporan['nilai'] ?>" min="0" max="100" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block font-semibold">Feedback</label>
                    <textarea name="feedback" class="w-full p-2 border rounded" rows="3"><?= htmlspecialchars($laporan['feedback']) ?></textarea>
                </div>
                <div class="flex justify-between items-center">
                    <a href="laporan_masuk.php" class="text-gray-600 hover:underline">⬅ Kembali</a>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>