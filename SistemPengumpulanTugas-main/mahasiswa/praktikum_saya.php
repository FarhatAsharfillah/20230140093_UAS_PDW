<?php
session_start();
        require_once '../config.php';
        require_once 'templates/header_mahasiswa.php';

// Cek apakah user login dan berperan sebagai mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    die("Akses ditolak. Halaman ini hanya untuk mahasiswa.");
}

$id_mahasiswa = $_SESSION['user_id'];

// Ambil data praktikum yang diikuti oleh mahasiswa ini
        $query = "
    SELECT p.id, p.nama, p.deskripsi, p.semester, p.tahun_ajaran
    FROM pendaftaran_praktikum dp
    JOIN praktikum p ON dp.id_praktikum = p.id
    WHERE dp.id_mahasiswa = ?
";

        $stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_mahasiswa);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Praktikum Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Praktikum yang Kamu Ikuti</h1>

        <?php if ($result->num_rows === 0): ?>
            <p class="text-center text-gray-600">Kamu belum mendaftar ke praktikum manapun.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="bg-white shadow-md rounded p-4">
                        <h2 class="text-xl font-semibold"><?= htmlspecialchars($row['nama']) ?></h2>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($row['deskripsi']) ?></p>
                        <p class="text-sm mt-2"><strong>Semester:</strong> <?= $row['semester'] ?> | <strong>Tahun:</strong> <?= $row['tahun_ajaran'] ?></p>
                        <!-- Tombol Detail Praktikum -->
                        <a href="detail_praktikum.php?id=1" class="inline-block mt-3 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Lihat Detail Tugas
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="mt-6 text-center">
            <a href="dashboard.php" class="text-blue-600 hover:underline">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>