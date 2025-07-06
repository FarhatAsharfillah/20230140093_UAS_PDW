<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config.php';
require_once 'templates/header.php';

// Cek role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

// Handle tambah praktikum
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $semester = $_POST['semester'];
    $tahun = $_POST['tahun_ajaran'];

    $stmt = $conn->prepare("INSERT INTO praktikum (nama, deskripsi, semester, tahun_ajaran) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $deskripsi, $semester, $tahun);
    $stmt->execute();

    header("Location: daftar_praktikum.php");
    exit;
}

// Ambil semua praktikum
$result = $conn->query("SELECT * FROM praktikum ORDER BY id DESC");
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Daftar Mata Praktikum</h1>

        <!-- Form Tambah -->
        <div class="bg-white shadow p-4 rounded mb-6">
            <h2 class="text-lg font-semibold mb-2">Tambah Praktikum</h2>
            <form method="POST">
                <input type="text" name="nama" placeholder="Nama Praktikum" class="w-full p-2 border rounded mb-2" required>
                <textarea name="deskripsi" placeholder="Deskripsi" class="w-full p-2 border rounded mb-2"></textarea>
                <input type="text" name="semester" placeholder="Semester (e.g. Ganjil)" class="w-full p-2 border rounded mb-2" required>
                <input type="text" name="tahun_ajaran" placeholder="Tahun Ajaran (e.g. 2024/2025)" class="w-full p-2 border rounded mb-2" required>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</button>
            </form>
        </div>

        <!-- Daftar Praktikum -->
        <table class="w-full bg-white shadow rounded">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="p-2">Nama</th>
                    <th class="p-2">Semester</th>
                    <th class="p-2">Tahun</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr class="border-t">
                        <td class="p-2"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="p-2"><?= $row['semester'] ?></td>
                        <td class="p-2"><?= $row['tahun_ajaran'] ?></td>
                        <td class="p-2 space-x-2">
                            <a href="edit_praktikum.php?id=<?= $row['id'] ?>" class="text-blue-600">Edit</a>
                            <a href="delete_praktikum.php?id=<?= $row['id'] ?>" class="text-red-600" onclick="return confirm('Yakin hapus?')">Hapus</a>
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