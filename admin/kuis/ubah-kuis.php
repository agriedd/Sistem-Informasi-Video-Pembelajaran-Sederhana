<?php

require_once "../../sambungan.php";

if(!($_SESSION['admin_aktif'] ?? false)){
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_kuis = "SELECT id_kuis, pertanyaan_kuis FROM kuis WHERE id_kuis = :id_kuis LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_kuis);
$query->execute([
	'id_kuis' => $_GET['id_kuis']
]);
$kuis = $query->fetchObject();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sambungan->query("START TRANSACTION");
	
	try {
		
		$query_ubah_kuis = "UPDATE kuis SET pertanyaan_kuis=:pertanyaan_kuis WHERE id_kuis=:id_kuis LIMIT 1";
		$query = $sambungan->prepare($query_ubah_kuis);
		$hasil = $query->execute([
			...$_POST,
			'id_kuis' => $kuis->id_kuis,
		]);
		if(!$hasil) throw new Error("Gagal menambahkan data!");
		$sambungan->query("COMMIT");
		header("location: ./info-kuis.php?id_kuis={$kuis->id_kuis}&sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./ubah-kuis.php?id_kuis={$kuis->id_kuis}&gagal=1&pesan={$th->getMessage()}");
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
		$aktif = "kuis";
		require_once('../navbar.php');
		?>
		
		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Ubah Kuis
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								ubah data kuis
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
UPDATE <code class="text-green-400">kuis</code> SET 
<code class="text-pink-400">pertanyaan_kuis</code>=<code class="text-orange-400">'<?=$kuis->pertanyaan_kuis ?>'</code> 
WHERE <code class="text-pink-400">id_kuis</code>=<code class="text-blue-400"><?=$kuis->id_kuis ?></code> LIMIT 1;
COMMIT;</pre>
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
								<label for="pertanyaan_kuis" class="text-sm text-slate-500">
									Pertanyaan Kuis
								</label>
								<input type="text" id="pertanyaan_kuis" name="pertanyaan_kuis" class="px-3 py-2 border rounded-md w-full" placeholder="Pertanyaan Kuis" value="<?=$kuis->pertanyaan_kuis ?>">
							</div>
							
							<div class="pt-6">
								<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400" type="submit">
									Simpan
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