<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id_praktikum = $_GET['id'] ?? null;
if (!$id_praktikum) {
    die("ID praktikum tidak ditemukan.");
}

// Ambil data praktikum
$praktikum = $conn->query("SELECT * FROM praktikum WHERE id = $id_praktikum")->fetch_assoc();

// Tambah modul
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_modul = $_POST['nama_modul'];
    $file = $_FILES['file_materi'];

    $nama_file = null;

    if ($file && $file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'docx', 'doc'])) {
            die("Format file tidak diperbolehkan.");
        }

        $nama_file = 'materi_' . time() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], "../uploads/materi/" . $nama_file);
    }

    $stmt = $conn->prepare("INSERT INTO modul (id_praktikum, nama_modul, file_materi) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_praktikum, $nama_modul, $nama_file);
    $stmt->execute();

    header("Location: kelola_modul.php?id=$id_praktikum");
    exit;
}

// Ambil modul untuk praktikum ini
$result = $conn->query("SELECT * FROM modul WHERE id_praktikum = $id_praktikum ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Modul - <?= htmlspecialchars($praktikum['nama']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Kelola Modul</h1>

        <!-- Form Tambah Modul -->
        <div class="bg-white p-4 rounded shadow mb-6">
            <h2 class="text-lg font-semibold mb-3">Tambah Modul</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="nama_modul" placeholder="Nama Modul" required class="w-full p-2 border rounded mb-3">
                <input type="file" name="file_materi" accept=".pdf,.doc,.docx" class="w-full mb-3">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Tambah Modul</button>
            </form>
        </div>

        <!-- Daftar Modul -->
        <table class="w-full bg-white rounded shadow">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Modul</th>
                    <th class="p-2 text-left">Materi</th>
                    <th class="p-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($modul = $result->fetch_assoc()) : ?>
                    <tr class="border-t">
                        <td class="p-2"><?= htmlspecialchars($modul['nama_modul']) ?></td>
                        <td class="p-2">
                            <?php if ($modul['file_materi']) : ?>
                                <a href="../uploads/materi/<?= $modul['file_materi'] ?>" target="_blank" class="text-blue-600 underline">Lihat File</a>
                            <?php else: ?>
                                <em class="text-gray-500">Tidak ada file</em>
                            <?php endif; ?>
                        </td>
                        <td class="p-2 space-x-2">
                            <a href="edit_modul.php?id=<?= $modul['id'] ?>&praktikum=<?= $id_praktikum ?>" class="text-blue-600">Edit</a>
                            <a href="delete_modul.php?id=<?= $modul['id'] ?>&praktikum=<?= $id_praktikum ?>" class="text-red-600" onclick="return confirm('Yakin hapus?')">Hapus</a>
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

</html>