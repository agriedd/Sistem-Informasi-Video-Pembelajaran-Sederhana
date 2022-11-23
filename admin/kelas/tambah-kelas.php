<?php

require_once "../../sambungan.php";

if(!($_SESSION['admin_aktif'] ?? false)){
	header("Location: ../../login/admin");
	exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sambungan->query("START TRANSACTION");
	
	try {
		$hasil = move_uploaded_file($_FILES['latar_kelas']['tmp_name'], "../../img/kelas/".$_FILES['latar_kelas']['name']);
		if(!$hasil) throw new Error("Gagal mengupload berkas!");
		$latar_kelas = "/img/kelas/".$_FILES['latar_kelas']['name'];
		$query_tambah_kelas = "INSERT INTO kelas (nama_kelas, deskripsi_singkat, keterangan, id_admin, tanggal, latar) VALUE (:nama_kelas, :deskripsi_singkat, :keterangan, :id_admin, NOW(), :latar)";
		$query = $sambungan->prepare($query_tambah_kelas);
		$hasil = $query->execute([
			...$_POST,
			'id_admin' => $_SESSION['admin_aktif'],
			'latar' => $latar_kelas
		]);
		if(!$hasil) throw new Error("Gagal menambahkan data!");
		$sambungan->query("COMMIT");
		header("location: ./index.php?sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./tambah-kelas.php?gagal=1&pesan={$th->getMessage()}");
		exit();
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Basis Data Lanjutan</title>
	<script src="/tailwind.js"></script>
	<style>
		.bg-pattern {
			background-color: rgba(244, 244, 255, 0);
			/* opacity: 0.6; */
			background-image: linear-gradient(#dbddff 1.2000000000000002px, transparent 1.2000000000000002px), linear-gradient(to right, #dbddff 1.2000000000000002px, rgba(244, 244, 255, 0) 1.2000000000000002px);
			background-size: 24px 24px;
		}
	</style>
</head>

<body class="m-0 p-0 bg-slate-50">
	<div class="grid grid-cols-1 justify-items-center gap-4">

		<?php
		$aktif = "kelas";
		require_once('../navbar.php');
		?>
		
		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Tambah Kelas
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Tambah data kelas baru
							</div>
						</div>
					</div>
					<?php 
						if($_GET['gagal'] ?? '0' == 1):
					?>
					<div class="p-6 bg-red-50 text-red-600 text-sm border-b border-red-200">
						terjadi kesalahan ketika menambahkan data, coba lagi!
					</div>
					<?php 
						endif;
					?>
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="p-6">
							<div class="mb-2">
								<label for="nama_kelas" class="text-sm text-slate-500">
									Nama Kelas
								</label>
								<input type="text" id="nama_kelas" name="nama_kelas" class="px-3 py-2 border rounded-md w-full" placeholder="Nama Kelas" value="Fotografi dengan smartphone">
							</div>
							<div class="mb-2">
								<label for="latar_kelas" class="text-sm text-slate-500">
									Gambar Latar Kelas
								</label>
								<input type="file" accept="image/*" id="latar_kelas" name="latar_kelas" class="px-3 py-2 border rounded-md w-full" placeholder="Latar Kelas">
							</div>
							<div class="mb-2">
								<label for="deskripsi_singkat" class="text-sm text-slate-500">
									Deskripsi Singkat
								</label>
								<input type="text" id="deskripsi_singkat" name="deskripsi_singkat" class="px-3 py-2 border rounded-md w-full" placeholder="Deskripsi Singkat" value="Belajar skill fotografi dengan smartphone">
							</div>
							<div class="mb-2">
								<label for="keterangan" class="text-sm text-slate-500">
									Keterangan panjang
								</label>
								<textarea type="text" id="keterangan" name="keterangan" rows="5" class="px-3 py-2 border rounded-md w-full" placeholder="Keterangan Kelas"></textarea>
							</div>
							<div class="pt-6">
								<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400" type="submit">
									Tambah
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

</html>