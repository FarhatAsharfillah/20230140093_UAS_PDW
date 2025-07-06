<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

// Ambil semua laporan dengan info lengkap
$sql = "
SELECT l.id, u.nama AS nama_mahasiswa, p.nama AS nama_praktikum, m.nama_modul, 
       l.file_laporan, l.nilai, l.feedback, l.tanggal_upload
FROM laporan_mahasiswa l
JOIN users u ON l.id_mahasiswa = u.id
JOIN modul m ON l.id_modul = m.id
JOIN praktikum p ON m.id_praktikum = p.id
ORDER BY l.tanggal_upload DESC
";

$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Daftar Laporan Masuk</h1>

        <table class="w-full bg-white rounded shadow">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="p-2">Mahasiswa</th>
                    <th class="p-2">Praktikum</th>
                    <th class="p-2">Modul</th>
                    <th class="p-2">File</th>
                    <th class="p-2">Nilai</th>
                    <th class="p-2">Feedback</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($laporan = $result->fetch_assoc()) : ?>
                    <tr class="border-t">
                        <td class="p-2"><?= htmlspecialchars($laporan['nama_mahasiswa']) ?></td>
                        <td class="p-2"><?= htmlspecialchars($laporan['nama_praktikum']) ?></td>
                        <td class="p-2"><?= htmlspecialchars($laporan['nama_modul']) ?></td>
                        <td class="p-2">
                            <a href="../uploads/laporan/<?= $laporan['file_laporan'] ?>" target="_blank" class="text-blue-600 underline">Unduh</a>
                        </td>
                        <td class="p-2"><?= $laporan['nilai'] ?? '-' ?></td>
                        <td class="p-2"><?= htmlspecialchars($laporan['feedback']) ?? '-' ?></td>
                        <td class="p-2">
                            <a href="nilai_laporan.php?id=<?= $laporan['id'] ?>" class="text-green-600 underline">Nilai</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-6 text-center">
            <a href="dashboard.php" class="text-blue-600 hover:underline">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</body>
<?php require_once 'templates/footer.php'; ?>

</html>