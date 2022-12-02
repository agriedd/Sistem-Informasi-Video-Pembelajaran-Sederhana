<?php

require_once "../../sambungan.php";

if(!($_SESSION['admin_aktif'] ?? false)){
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_video = "SELECT id_video, judul_video, keterangan, video, id_kelas, tanggal FROM video_pembelajaran WHERE id_video = :id_video LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_video);
$query->execute([
	'id_video' => $_GET['id_video']
]);
$video = $query->fetchObject();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sambungan->query("START TRANSACTION");
	
	try {
		$query_tambah_kuis = "INSERT INTO kuis (pertanyaan_kuis, id_video, tanggal) VALUE (:pertanyaan_kuis, :id_video, NOW())";
		$query = $sambungan->prepare($query_tambah_kuis);
		$hasil = $query->execute([
			'pertanyaan_kuis' => $_POST['pertanyaan_kuis'],
			'id_video' => $video->id_video
		]);
		if(!$hasil) throw new Error("Gagal menambahkan data!");

		$query_kuis_terakhir = "SELECT LAST_INSERT_ID() as id_kuis";
		$query = $sambungan->query($query_kuis_terakhir);
		$kuis = $query->fetchObject();

		foreach($_POST['jawaban'] as $key => $jawaban){
			$status_jawaban = in_array("{$key}", $_POST['status_benar']);

			$query_tambah_opsi_jawaban = "INSERT INTO opsi_jawaban (jawaban, status_benar, id_kuis) VALUE (:jawaban, :status_benar, :id_kuis)";
			$query = $sambungan->prepare($query_tambah_opsi_jawaban);
			$hasil = $query->execute([
				'jawaban' => $jawaban,
				'id_kuis' => $kuis->id_kuis,
				'status_benar' => $status_jawaban ? 'benar' : 'salah'
			]);
			if(!$hasil) throw new Error("Gagal menambahkan data!");
		}

		$sambungan->query("COMMIT");
		header("location: ./info-video.php?id_video={$video->id_video}&sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./tambah-kuis.php?id_video={$video->id_video}&gagal=1&pesan={$th->getMessage()}");
		exit();
	}
}

$jumlah = $_GET['jumlah'] ?? 1;

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
								Tambah Kuis
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Tambah kuis baru
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
INSERT INTO <code class="text-green-400">kuis</code> (
		<code class="text-pink-400">pertanyaan_kuis</code>, <code class="text-pink-400">id_video</code>, <code class="text-pink-400">tanggal</code>
	) VALUE (
		<code class="text-orange-400">'berapa nilai dari 1+1?'</code>, <code class="text-blue-400"><?=$_GET['id_video'] ?></code>, <code class="text-blue-400">NOW()</code>
	);
SELECT <code class="text-blue-400">LAST_INSERT_ID()</code> as <code class="text-pink-400">id_kuis</code>;
INSERT INTO <code class="text-green-400">opsi_jawaban</code> (<code class="text-pink-400">jawaban</code>, <code class="text-pink-400">status_benar</code>, <code class="text-pink-400">id_kuis</code>) VALUE (<code class="text-orange-400">'1'</code>, <code class="text-orange-400">'salah'</code>, <code class="text-blue-400">1</code>);
INSERT INTO <code class="text-green-400">opsi_jawaban</code> (<code class="text-pink-400">jawaban</code>, <code class="text-pink-400">status_benar</code>, <code class="text-pink-400">id_kuis</code>) VALUE (<code class="text-orange-400">'2'</code>, <code class="text-orange-400">'benar'</code>, <code class="text-blue-400">1</code>);
INSERT INTO <code class="text-green-400">opsi_jawaban</code> (<code class="text-pink-400">jawaban</code>, <code class="text-pink-400">status_benar</code>, <code class="text-pink-400">id_kuis</code>) VALUE (<code class="text-orange-400">'3'</code>, <code class="text-orange-400">'salah'</code>, <code class="text-blue-400">1</code>);
INSERT INTO <code class="text-green-400">opsi_jawaban</code> (<code class="text-pink-400">jawaban</code>, <code class="text-pink-400">status_benar</code>, <code class="text-pink-400">id_kuis</code>) VALUE (<code class="text-orange-400">'4'</code>, <code class="text-orange-400">'salah'</code>, <code class="text-blue-400">1</code>);
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
					<form action="" method="GET">
						<div class="p-6 border-b">
							<div class="mb-2">
								<label for="jumlah" class="text-sm text-slate-500">
									Jumlah Opsi Jawaban
								</label>
								<div class="flex gap-3 items-center">
									<input type="hidden" name="id_video" value="<?=$_GET['id_video'] ?? null ?>">
									<input type="text" id="jumlah" name="jumlah" class="px-3 py-2 border rounded-md w-full" placeholder="Jumlah" value="<?=$jumlah ?>">
									<button class="px-6 py-2 rounded-md bg-slate-500 text-white border-slate-400" type="submit">
										Proses
									</button>
								</div>
							</div>
						</div>
					</form>
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="p-6">
							<div class="mb-2">
								<label for="pertanyaan_kuis" class="text-sm text-slate-500">
									Pertanyaan Kuis
								</label>
								<input type="text" id="pertanyaan_kuis" name="pertanyaan_kuis" class="px-3 py-2 border rounded-md w-full" placeholder="Pertanyaan Kuis" value="">
							</div>
							
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
							<?php 
								for($i = 0; $i < $jumlah; $i++):
							?>
							<div class="mb-2">
								<div class="flex gap-3 items-center">
									<div class="text-sm text-slate-500">
										<?=$i + 1 ?>.
									</div>
									<input type="text" id="jawaban" name="jawaban[]" class="px-3 py-2 border rounded-md w-full" placeholder="Opsi Jawaban">
									<div class="w-11 flex justify-center">
										<input type="checkbox" id="status_benar" name="status_benar[]" class="accent-blue-500 h-4 w-4 cursor-pointer" value="<?=$i ?>">
									</div>
								</div>
							</div>
							<?php
								endfor;
							?>
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