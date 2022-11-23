<?php

function cetak($tabel)
{
	echo "<kbd>Berhasil membuat data {$tabel}</kbd>";
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

$query_menggunakan_database = "USE {$database_name}";

$sambungan->query($query_menggunakan_database);

/**
 * membuat data admin
 * 
 */
$query_tabel_admin = "INSERT INTO admin (
	nama,
	jenis_kelamin,
	tanggal_lahir,
	email,
	kata_sandi
) VALUES (
	'admin',
	'l',
	'2001-01-01',
	'admin@vidpen.com',
	MD5('password')
)";
try{
	$sambungan->query($query_tabel_admin) && cetak("admin");
} catch(Exception $e) {
	echo "<kbd style='color: red'>Data admin sudah ada!</kbd>";
}

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
	<a href="/">
		<button>
			kembali halaman awal
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