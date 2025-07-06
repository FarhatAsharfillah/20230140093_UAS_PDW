<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika pengguna belum login atau bukan asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

require_once '../config.php';

// Ambil ID praktikum pertama yang tersedia (untuk Kelola Modul)
$default_praktikum_id = null;
$praktikum_result = $conn->query("SELECT id FROM praktikum ORDER BY id ASC LIMIT 1");
if ($praktikum_result && $row = $praktikum_result->fetch_assoc()) {
    $default_praktikum_id = $row['id'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Asisten - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="p-6 text-center border-b border-gray-700">
            <h3 class="text-xl font-bold">Panel Asisten</h3>
            <p class="text-sm text-gray-400 mt-1"><?php echo htmlspecialchars($_SESSION['nama']); ?></p>
        </div>
        <nav class="flex-grow">
            <ul class="space-y-2 p-4">
                <?php 
                    $activeClass = 'bg-gray-900 text-white';
                    $inactiveClass = 'text-gray-300 hover:bg-gray-700 hover:text-white';
                ?>
            
                <!-- Dashboard -->
                <li>
                    <a href="dashboard.php" class="<?php echo ($activePage == 'dashboard') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-3 rounded-md transition-colors duration-200">
                        <span class="mr-3">🏠</span>
                        <span>Dashboard</span>
                    </a>
                </li>
            
                <!-- Kelola Praktikum -->
                <li>
                    <a href="daftar_praktikum.php" class="<?php echo ($activePage == 'modul') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-3 rounded-md transition-colors duration-200">
                        <span class="mr-3">📚</span>
                        <span>Kelola Praktikum</span>
                    </a>
                </li>
            
                <!-- Kelola Modul -->
                <?php if (isset($default_praktikum_id)): ?>
                <li>
                    <a href="kelola_modul.php?id=<?= $default_praktikum_id ?>" class="<?php echo ($activePage == 'modul') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-3 rounded-md transition-colors duration-200">
                        <span class="mr-3">📋</span>
                        <span>Kelola Modul</span>
                    </a>
                </li>
                <?php endif; ?>
            
                <!-- Kelola Mahasiswa -->
                <li>
                    <a href="kelola_mahasiswa.php" class="<?php echo ($activePage == 'mahasiswa') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-3 rounded-md transition-colors duration-200">
                        <span class="mr-3">👥</span>
                        <span>Kelola Mahasiswa dan Asisten</span>
                    </a>
                </li>
            
                <!-- Laporan Masuk -->
                <li>
                    <a href="laporan_masuk.php" class="<?php echo ($activePage == 'laporan') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-3 rounded-md transition-colors duration-200">
                        <span class="mr-3">📥</span>
                        <span>Laporan Masuk</span>
                    </a>
                </li>
            </ul>
            
            
        </nav>
    </aside>

    <main class="flex-1 p-6 lg:p-10">
        <header class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
            <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                Logout
            </a>
        </header>
