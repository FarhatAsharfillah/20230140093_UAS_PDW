<?php
session_start();
        require_once '../config.php';
        require_once 'templates/header_mahasiswa.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$id_mahasiswa = $_SESSION['user_id'];

// Ambil semua modul dari semua praktikum yang diikuti oleh mahasiswa
$sql = "SELECT m.*, p.nama AS nama_praktikum 
        FROM modul m
        JOIN praktikum p ON m.id_praktikum = p.id
        JOIN pendaftaran_praktikum pp ON pp.id_praktikum = p.id
        WHERE pp.id_mahasiswa = ?
        ORDER BY p.nama ASC, m.id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_mahasiswa);
$stmt->execute();
$modul_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Modul Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Modul dari Praktikum yang Diikuti</h1>

        <?php if ($modul_result->num_rows === 0): ?>
            <p class="text-gray-600 text-center">Belum ada modul yang tersedia.</p>
        <?php else: ?>
            <?php while ($modul = $modul_result->fetch_assoc()):
                // Cek laporan yang sudah dikumpulkan
                $cek_laporan = $conn->prepare("SELECT * FROM laporan_mahasiswa WHERE id_mahasiswa = ? AND id_modul = ?");
                $cek_laporan->bind_param("ii", $id_mahasiswa, $modul['id']);
                $cek_laporan->execute();
                $laporan = $cek_laporan->get_result()->fetch_assoc();
            ?>
                <div class="bg-white p-4 rounded shadow mb-6">
                    <h2 class="text-lg font-semibold"><?= htmlspecialchars($modul['nama_modul']) ?>
                    </h2>

                    <p class="text-sm text-gray-600">
                        Modul Materi dan Tugas:
                        <?php if ($modul['file_materi']): ?>
                            <a href="../uploads/materi/<?= $modul['file_materi'] ?>" class="text-blue-600 underline" download>Download Modul Materi dan Tugas</a>
                        <?php else: ?>
                            <em class="text-red-400">Belum ada</em>
                        <?php endif; ?>
                    </p>

                    <!-- Upload Form -->
                    
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <div class="mt-6 text-center">
            <a href="dashboard.php" class="text-blue-600 hover:underline">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>