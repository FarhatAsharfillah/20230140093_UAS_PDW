<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

// Ambil semua user
$result = $conn->query("SELECT * FROM users ORDER BY role, nama");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Mahasiswa & Asisten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Kelola Mahasiswa & Asisten</h1>
            <a href="tambah_user.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">+ Tambah Pengguna</a>
        </div>

        <table class="w-full bg-white rounded shadow">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-left">Email</th>
                    <th class="p-2 text-left">Role</th>
                    <th class="p-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= htmlspecialchars($user['nama']) ?></td>
                        <td class="p-2"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="p-2 capitalize"><?= htmlspecialchars($user['role']) ?></td>
                        <td class="p-2 space-x-2">
                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="text-blue-600">Edit</a>
                            <a href="hapus_user.php?id=<?= $user['id'] ?>" class="text-red-600" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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