<?php

require_once "../../sambungan.php";

if(!($_SESSION['admin_aktif'] ?? false)){
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_opsi_jawaban = "SELECT id_opsi_jawaban, jawaban, status_benar, id_kuis FROM opsi_jawaban WHERE id_opsi_jawaban = :id_opsi_jawaban LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_opsi_jawaban);
$query->execute([
	'id_opsi_jawaban' => $_GET['id_opsi_jawaban']
]);
$opsi_jawaban = $query->fetchObject();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sambungan->query("START TRANSACTION");
	
	try {
		
		$query_ubah_opsi_jawaban = "UPDATE opsi_jawaban SET jawaban=:jawaban WHERE id_opsi_jawaban=:id_opsi_jawaban LIMIT 1";
		$query = $sambungan->prepare($query_ubah_opsi_jawaban);
		$hasil = $query->execute([
			...$_POST,
			'id_opsi_jawaban' => $opsi_jawaban->id_opsi_jawaban,
		]);
		if(!$hasil) throw new Error("Gagal menambahkan data!");
		// $sambungan->query("COMMIT");
		header("location: ./info-kuis.php?id_kuis={$opsi_jawaban->id_kuis}&sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./ubah-opsi-jawaban.php?id_opsi_jawaban={$opsi_jawaban->id_opsi_jawaban}&gagal=1&pesan={$th->getMessage()}");
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
		$aktif = "opsi_jawaban";
		require_once('../navbar.php');
		?>
		
		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Ubah Opsi Jawaban
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								ubah data opsi jawaban
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
UPDATE <code class="text-green-400">opsi_jawaban</code> SET 
	<code class="text-pink-400">jawaban</code>=<code class="text-orange-400">'<?=$opsi_jawaban->jawaban ?>'</code> 
	WHERE <code class="text-pink-400">id_opsi_jawaban</code>=<code class="text-blue-400"><?=$opsi_jawaban->id_opsi_jawaban ?></code> LIMIT 1;
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
								<div class="flex gap-3 items-center justify-between">
									<div class="text-sm text-slate-500">
										Opsi Jawaban
									</div>
									<div class="text-sm text-slate-500 w-11">
										Status benar
									</div>
								</div>
							</div>
							<div class="mb-2">
								<div class="flex gap-3 items-center">
									<div class="text-sm text-slate-500">
									</div>
									<input type="text" id="jawaban" name="jawaban" class="px-3 py-2 border rounded-md w-full" placeholder="Opsi Jawaban" value="<?=$opsi_jawaban->jawaban ?>">
									<div class="w-11 flex justify-center">
										<input type="checkbox" id="status_benar" name="status_benar" class="accent-blue-500 h-4 w-4 cursor-pointer" value="<?= $no_urutan ?>" <?= $opsi_jawaban->status_benar == 'benar' ? 'cheked' : null ?>>
									</div>
								</div>
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