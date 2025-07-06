<?php
session_start();
            require_once '../config.php';
            require_once 'templates/header_mahasiswa.php';

// Cek apakah user sudah login dan peran adalah mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$id_mahasiswa = $_SESSION['user_id'];

// Tangani POST saat tombol "Daftar"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_praktikum'])) {
    $id_praktikum = $_POST['id_praktikum'];

    // Cek apakah sudah mendaftar sebelumnya
    $cek = $conn->prepare("SELECT * FROM pendaftaran_praktikum WHERE id_mahasiswa = ? AND id_praktikum = ?");
    $cek->bind_param("ii", $id_mahasiswa, $id_praktikum);
    $cek->execute();
    $cek_result = $cek->get_result();

    if ($cek_result->num_rows === 0) {
        // Belum daftar, insert
        $stmt = $conn->prepare("INSERT INTO pendaftaran_praktikum (id_mahasiswa, id_praktikum) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_mahasiswa, $id_praktikum);
        $stmt->execute();
        $success = "Berhasil mendaftar ke praktikum!";
    } else {
        $error = "Anda sudah terdaftar di praktikum ini.";
    }
}

$query = "SELECT * FROM praktikum";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Katalog Mata Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Katalog Mata Praktikum</h1>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="bg-white shadow-md rounded p-4">
                    <h2 class="text-xl font-semibold"><?= htmlspecialchars($row['nama']) ?></h2>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    <p class="text-sm mt-2"><strong>Semester:</strong> <?= $row['semester'] ?> | <strong>Tahun:</strong> <?= $row['tahun_ajaran'] ?></p>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="id_praktikum" value="<?= $row['id'] ?>">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Daftar</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="mt-8 text-center">
            <a href="dashboard.php" class="text-blue-600 hover:underline">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>