<?php
session_start();
require_once '../config.php';
require_once 'templates/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak.");
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID pengguna tidak ditemukan.");
}

// Ambil data pengguna
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Pengguna tidak ditemukan.");
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($nama) || empty($email) || empty($role)) {
        $message = "Nama, email, dan role wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
    } else {
        // Update data
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, password=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $nama, $email, $hashed_password, $role, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $nama, $email, $role, $id);
        }

        if ($stmt->execute()) {
            header("Location: kelola_mahasiswa.php");
            exit;
        } else {
            $message = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Edit Pengguna</h1>

        <?php if ($message): ?>
            <p class="text-red-600 mb-4"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="w-full p-2 border rounded" required>
                    <option value="mahasiswa" <?= $user['role'] == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                    <option value="asisten" <?= $user['role'] == 'asisten' ? 'selected' : '' ?>>Asisten</option>
                </select>
            </div>

            <div class="flex justify-between">
                <a href="kelola_mahasiswa.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Kembali</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</body>

</html>