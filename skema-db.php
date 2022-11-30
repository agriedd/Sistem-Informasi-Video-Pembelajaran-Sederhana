<?php

function cetak($tabel)
{
	echo "<kbd>Berhasil membuat tabel {$tabel}</kbd>";
}

/**
 * membuat database penjualan
 * property sambungan database
 * 
 */
$username = "root";
$password = "";
$database_name = "video_pembelajaran";

$sambungan = new PDO("mysql:host=localhost", $username, $password);

$query_membuat_database = "CREATE DATABASE IF NOT EXISTS {$database_name}";
$query_menggunakan_database = "USE {$database_name}";

$sambungan->query($query_membuat_database);
$sambungan->query($query_menggunakan_database);

/**
 * membuat tabel 📁
 * 
 */
$query_tabel_admin = "CREATE TABLE IF NOT EXISTS admin (
	id_admin BIGINT AUTO_INCREMENT,
	nama VARCHAR(100) NOT NULL,
	jenis_kelamin ENUM('l', 'p') NOT NULL,
	tanggal_lahir DATE NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE,
	kata_sandi VARCHAR(255) NOT NULL,
	PRIMARY KEY (id_admin)
)";
$sambungan->query($query_tabel_admin) && cetak("admin");
$query_tabel_pengguna = "CREATE TABLE IF NOT EXISTS pengguna (
	id_pengguna BIGINT AUTO_INCREMENT,
	nama VARCHAR(100) NOT NULL,
	jenis_kelamin ENUM('l', 'p') NOT NULL,
	tanggal_lahir DATE NOT NULL,
	email VARCHAR(255) NOT NULL UNIQUE,
	kata_sandi VARCHAR(255) NOT NULL,
	PRIMARY KEY (id_pengguna)
)";
$sambungan->query($query_tabel_pengguna) && cetak("pengguna");
$query_tabel_kelas = "CREATE TABLE IF NOT EXISTS kelas (
	id_kelas BIGINT AUTO_INCREMENT,
	nama_kelas VARCHAR(100) NOT NULL,
	id_admin BIGINT NOT NULL,
	tanggal DATETIME NOT NULL,
	deskripsi_singkat VARCHAR(255) NULL,
	latar VARCHAR(255) NULL,
	keterangan TEXT NULL,
	PRIMARY KEY (id_kelas),
	FOREIGN KEY (id_admin) REFERENCES admin(id_admin)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_kelas) && cetak("kelas");

$query_tabel_video = "CREATE TABLE IF NOT EXISTS video_pembelajaran (
	id_video BIGINT AUTO_INCREMENT,
	judul_video VARCHAR(190) NOT NULL,
	id_kelas BIGINT NOT NULL,
	tanggal DATETIME NOT NULL,
	video VARCHAR(255) NOT NULL,
	keterangan TEXT NULL,
	urutan BIGINT NOT NULL DEFAULT(0),
	PRIMARY KEY (id_video),
	FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_video) && cetak("video");

$query_tabel_pertanyaan = "CREATE TABLE IF NOT EXISTS pertanyaan (
	id_pertanyaan BIGINT AUTO_INCREMENT,
	pertanyaan VARCHAR(190) NOT NULL,
	id_video BIGINT NOT NULL,
	id_pengguna BIGINT NOT NULL,
	tanggal DATETIME NOT NULL,
	dukungan_naik BIGINT UNSIGNED NOT NULL,
	dukungan_turun BIGINT UNSIGNED NOT NULL,
	PRIMARY KEY (id_pertanyaan),
	FOREIGN KEY (id_video) REFERENCES video_pembelajaran(id_video)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_pertanyaan) && cetak("pertanyaan");

$query_tabel_balasan = "CREATE TABLE IF NOT EXISTS balasan (
	id_balasan BIGINT AUTO_INCREMENT,
	balasan VARCHAR(190) NOT NULL,
	id_pertanyaan BIGINT NOT NULL,
	id_pengguna BIGINT NOT NULL,
	tanggal DATETIME NOT NULL,
	dukungan_naik BIGINT UNSIGNED NOT NULL,
	dukungan_turun BIGINT UNSIGNED NOT NULL,
	PRIMARY KEY (id_balasan),
	FOREIGN KEY (id_pertanyaan) REFERENCES pertanyaan(id_pertanyaan)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_balasan) && cetak("balasan");

$query_tabel_kuis = "CREATE TABLE IF NOT EXISTS kuis (
	id_kuis BIGINT AUTO_INCREMENT,
	pertanyaan_kuis VARCHAR(255) NOT NULL,
	id_video BIGINT NOT NULL,
	tanggal DATETIME NOT NULL,
	PRIMARY KEY (id_kuis),
	FOREIGN KEY (id_video) REFERENCES video_pembelajaran(id_video)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_kuis) && cetak("kuis");

$query_tabel_opsi_jawaban = "CREATE TABLE IF NOT EXISTS opsi_jawaban (
	id_opsi_jawaban BIGINT AUTO_INCREMENT,
	id_kuis BIGINT NOT NULL,
	jawaban VARCHAR(255) NOT NULL,
	status_benar ENUM('benar', 'salah') NOT NULL,
	PRIMARY KEY (id_opsi_jawaban),
	FOREIGN KEY (id_kuis) REFERENCES kuis(id_kuis)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_opsi_jawaban) && cetak("opsi_jawaban");

$query_tabel_kuis_pengguna = "CREATE TABLE IF NOT EXISTS kuis_pengguna (
	id_kuis_pengguna BIGINT AUTO_INCREMENT,
	id_kuis BIGINT NOT NULL,
	id_pengguna BIGINT NOT NULL,
	tanggal DATETIME NOT NULL,
	skor DECIMAL(5,2) NOT NULL,
	PRIMARY KEY (id_kuis_pengguna),
	FOREIGN KEY (id_kuis) REFERENCES kuis(id_kuis)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)";
$sambungan->query($query_tabel_kuis_pengguna) && cetak("kuis_pengguna");

?>
<div>
	<a href="./drop-database.php">
		<button>
			reset database
		</button>
	</a>
	<a href="./db-seed.php">
		<button>
			buat admin
		</button>
	</a>
</div>
<style>
	body{
		display: flex;
		flex-direction: column;
		gap: .25rem;
		max-width: 500px;
		margin: 0 auto;
		padding: .25rem;
	}
	kbd{
		background-color: #333;
		padding: .25rem;
		border-radius: .15rem;
		color: greenyellow;
	}
</style>