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

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, latar, tanggal FROM kelas WHERE id_kelas = :id_kelas LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);
$kelas = $query->fetchObject();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sambungan->query("START TRANSACTION");
	
	try {
		
		$query_ubah_video = "UPDATE video_pembelajaran SET judul_video=:judul_video, video=:video, keterangan=:keterangan WHERE id_video=:id_video LIMIT 1";
		$query = $sambungan->prepare($query_ubah_video);
		$hasil = $query->execute([
			...$_POST,
			'id_video' => $video->id_video,
		]);
		if(!$hasil) throw new Error("Gagal menambahkan data!");
		$sambungan->query("COMMIT");
		header("location: ./info-video.php?id_video={$video->id_video}&sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./ubah-video.php?id_kelas={$kelas->id_kelas}&id_video={$video->id_video}&gagal=1&pesan={$th->getMessage()}");
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
								Ubah Video
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								ubah data video
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
UPDATE <code class="text-green-400">video_pembelajaran</code> SET 
<code class="text-pink-400">judul_video</code>=<code class="text-orange-400">'<?=$video->judul_video ?>'</code>, 
<code class="text-pink-400">video</code>=<code class="text-orange-400">'<?=$video->video ?>'</code>, 
<code class="text-pink-400">keterangan</code>=<code class="text-orange-400">'<?=$video->keterangan ?>'</code>
WHERE <code class="text-pink-400">id_video</code>=<code class="text-blue-400"><?=$video->id_video ?></code> LIMIT 1;
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
								<label for="judul_video" class="text-sm text-slate-500">
									Judul Video
								</label>
								<input type="text" id="judul_video" name="judul_video" class="px-3 py-2 border rounded-md w-full" placeholder="Nama Kelas" value="<?=$video->judul_video ?>">
							</div>
							<div class="mb-2">
								<label for="video" class="text-sm text-slate-500">
									Link Video Youtube
								</label>
								<input type="text" id="video" name="video" class="px-3 py-2 border rounded-md w-full" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ" value="<?=$video->video ?>">
							</div>
							<div class="mb-2">
								<label for="keterangan" class="text-sm text-slate-500">
									Keterangan
								</label>
								<textarea type="text" id="keterangan" name="keterangan" rows="5" class="px-3 py-2 border rounded-md w-full" placeholder="Keterangan Kelas Video"><?=$video->keterangan ?></textarea>
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