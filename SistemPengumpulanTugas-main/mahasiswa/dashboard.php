<?php

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

?>


<div class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white p-8 rounded-xl shadow-lg mb-8">
    <h1 class="text-3xl font-bold">Selamat Datang Kembali, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
    <p class="mt-2 opacity-90">Terus semangat dalam menyelesaikan semua modul praktikummu.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-blue-600">3</div>
        <div class="mt-2 text-lg text-gray-600">Praktikum Diikuti</div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-green-500">8</div>
        <div class="mt-2 text-lg text-gray-600">Tugas Selesai</div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center">
        <div class="text-5xl font-extrabold text-yellow-500">4</div>
        <div class="mt-2 text-lg text-gray-600">Tugas Menunggu</div>
    </div>

</div>

<div class="bg-white p-6 rounded-xl shadow-md">
    <div>
        <li class="flex items-start p-3">
            <a href="modul_saya.php" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md text-lg shadow">
                📚 Lihat Modul Saya
            </a>
        </li>
    </div>
    <li class="flex items-start p-3">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi Terbaru</h3>
    </li>
    <div class="mt-0 text-center">
        <!-- Ambil id praktikum pertama -->
        <?php
        $stmt = $conn->prepare("SELECT id FROM pendaftaran_praktikum WHERE id_mahasiswa = ? LIMIT 1");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $id_default = $res['id'] ?? null;
        ?>

    </div>

    <?php
    // Ambil praktikum yang diikuti oleh mahasiswa
    $id_mahasiswa = $_SESSION['user_id'];
    $sql = "SELECT p.id, p.nama, p.semester, p.tahun_ajaran 
        FROM praktikum p 
        JOIN pendaftaran_praktikum pp ON pp.id_praktikum = p.id
        WHERE pp.id_mahasiswa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_mahasiswa);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <ul class="space-y-8">

        <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
            <span class="text-xl mr-4">🔔</span>
            <div>
                Nilai untuk <a href="#" class="font-semibold text-blue-600 hover:underline">Modul 1: HTML & CSS</a> telah diberikan.
            </div>
        </li>

        <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
            <span class="text-xl mr-4">⏳</span>
            <div>
                Batas waktu pengumpulan laporan untuk <a href="#" class="font-semibold text-blue-600 hover:underline">Modul 2: PHP Native</a> adalah besok!
            </div>
        </li>

        <li class="flex items-start p-3">
            <span class="text-xl mr-4">✅</span>
            <div>
                Anda berhasil mendaftar pada mata praktikum <a href="#" class="font-semibold text-blue-600 hover:underline">Jaringan Komputer</a>.
            </div>
        </li>

    </ul>
</div>


<?php
// Panggil Footer
require_once 'templates/footer_mahasiswa.php';
?>