CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','asisten') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE praktikum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    semester VARCHAR(10),
    tahun_ajaran VARCHAR(20)
);

CREATE TABLE pendaftaran_praktikum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_praktikum INT NOT NULL,
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_mahasiswa, id_praktikum)
);

CREATE TABLE modul (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_praktikum INT NOT NULL,
    nama_modul VARCHAR(100) NOT NULL,
    file_materi VARCHAR(255),
    FOREIGN KEY (id_praktikum) REFERENCES praktikum(id)
);

CREATE TABLE laporan_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_modul INT NOT NULL,
    file_laporan VARCHAR(255),
    nilai INT,
    feedback TEXT,
    tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_mahasiswa, id_modul)
);

CREATE TABLE modul (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_praktikum INT NOT NULL,
    nama_modul VARCHAR(100) NOT NULL,
    file_materi VARCHAR(255),
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_praktikum) REFERENCES praktikum(id)
);

CREATE TABLE laporan_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_modul INT NOT NULL,
    file_laporan VARCHAR(255),
    nilai INT,
    feedback TEXT,
    tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (id_mahasiswa, id_modul)
);
