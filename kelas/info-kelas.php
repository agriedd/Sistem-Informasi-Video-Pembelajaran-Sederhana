<?php

require_once "../sambungan.php";

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, keterangan, latar, tanggal FROM kelas WHERE id_kelas = :id_kelas LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);
$kelas = $query->fetchObject();

if(isset($_GET['id_video'])){
	$query_mengambil_record_video = "SELECT * FROM video_pembelajaran
	WHERE id_kelas = :id_kelas
	AND id_video = :id_video
	ORDER BY urutan ASC, tanggal ASC LIMIT 1";
	$query = $sambungan->prepare($query_mengambil_record_video);
	$query->execute([
		'id_kelas' => $_GET['id_kelas'],
		'id_video' => $_GET['id_video']
	]);
	$video = $query->fetchObject();
} else {
	$query_mengambil_record_video_pertama = "SELECT * FROM video_pembelajaran
	WHERE id_kelas = :id_kelas
	ORDER BY urutan ASC, tanggal ASC LIMIT 1";
	$query = $sambungan->prepare($query_mengambil_record_video_pertama);
	$query->execute([
		'id_kelas' => $_GET['id_kelas']
	]);
	$video = $query->fetchObject();
}



if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_POST['id_pertanyaan'])){
		/**
		 * balasan
		 * 
		 */
		$sambungan->query("START TRANSACTION");
		try {
			$query_tambah_kelas = "INSERT INTO balasan (balasan, id_pertanyaan, id_pengguna, tanggal, dukungan_naik, dukungan_turun) VALUE (:balasan, :id_pertanyaan, :id_pengguna, NOW(), 0, 0)";
			$query = $sambungan->prepare($query_tambah_kelas);
			$hasil = $query->execute([
				...$_POST,
				'id_pengguna' => $_SESSION['pengguna_aktif']
			]);
			if(!$hasil) throw new Error("Gagal menambahkan pertanyaan!");
			$sambungan->query("COMMIT");
			header("location: ./info-kelas.php?id_kelas={$_GET['id_kelas']}&id_video={$video->id_video}&sukses=1");
			exit();
		} catch (\Throwable $th) {
			$sambungan->query("ROLLBACK");
			header("location: ./info-kelas.php?id_kelas={$_GET['id_kelas']}&id_video={$video->id_video}&gagal=1&pesan={$th->getMessage()}");
			exit();
		}
	} else {
		$sambungan->query("START TRANSACTION");
		
		try {
			$query_tambah_kelas = "INSERT INTO pertanyaan (pertanyaan, id_video, id_pengguna, tanggal, dukungan_naik, dukungan_turun) VALUE (:pertanyaan, :id_video, :id_pengguna, NOW(), 0, 0)";
			$query = $sambungan->prepare($query_tambah_kelas);
			$hasil = $query->execute([
				...$_POST,
				'id_video' => $video->id_video,
				'id_pengguna' => $_SESSION['pengguna_aktif']
			]);
			if(!$hasil) throw new Error("Gagal menambahkan pertanyaan!");
			$sambungan->query("COMMIT");
			header("location: ./info-kelas.php?id_kelas={$_GET['id_kelas']}&id_video={$video->id_video}&sukses=1");
			exit();
		} catch (\Throwable $th) {
			$sambungan->query("ROLLBACK");
			header("location: ./info-kelas.php?id_kelas={$_GET['id_kelas']}&id_video={$video->id_video}&gagal=1&pesan={$th->getMessage()}");
			exit();
		}
		
	}
}

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, latar, tanggal FROM kelas
WHERE id_kelas <> :id_kelas
ORDER BY tanggal DESC";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);

$query_mengambil_record_video = "SELECT id_video, judul_video, keterangan, video, tanggal FROM video_pembelajaran
WHERE id_kelas = :id_kelas
ORDER BY urutan ASC, tanggal ASC";
$query_video = $sambungan->prepare($query_mengambil_record_video);
$query_video->execute([
	'id_kelas' => $_GET['id_kelas']
]);

if($video){
	$query_mengambil_record_pertanyaan = "SELECT pertanyaan.*, dukungan_naik - dukungan_turun AS poin_dukungan,
	pengguna.nama 
	FROM pertanyaan
	LEFT JOIN pengguna ON pengguna.id_pengguna = pertanyaan.id_pengguna
	WHERE id_video = :id_video";
	$query_pertanyaan = $sambungan->prepare($query_mengambil_record_pertanyaan);
	$query_pertanyaan->execute([
		'id_video' => $video->id_video
	]);
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

		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap lg:flex-wrap-reverse">
			<div class="w-full max-w-2xl grid lg:grid-cols-1 gap-4">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								<?= $kelas->nama_kelas ?>
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								<?= $kelas->keterangan ?? $kelas->deskripsi_singkat ?>
							</div>
						</div>
					</div>
					<?php
						if($video):
					?>
					
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">*</code> FROM <code class="text-green-400">video_pembelajaran</code>
	WHERE <code class="text-pink-400">id_kelas</code> = <code class="text-blue-400"><?=$_GET['id_kelas'] ?></code>
	AND <code class="text-pink-400">id_video</code> = <code class="text-blue-400"><?=$video->id_video ?></code>
	ORDER BY <code class="text-pink-400">urutan</code> ASC, <code class="text-pink-400">tanggal</code> ASC LIMIT 1;</pre>
						</div>
					</div>
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-3xl font-black text-slate-500 mb-3">
								<?= $video->judul_video ?>
							</div>
							<div class="text-slate-500 group-hover:text-slate-500 max-w-sm">
								<?= $video->keterangan ?>
							</div>
						</div>
					</div>
					<div class="flex justify-center">
						<div>
							<iframe width="560" height="315" src="https://www.youtube.com/embed/<?= preg_replace("/^([^v]+v=)([^&]+)(&?.*)$/i", "$2", $video->video) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
					<?php
						endif;
					?>
				</div>
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between">
						<div class="flex items-center justify-center">
							<div class="text-xl font-black text-slate-500">
								Kuis
							</div>
						</div>
						<div class="flex items-center justify-center">
							<a href="/kelas/kuis.php?id_video=<?=$video->id_video ?>" class="px-5 py-2 bg-orange-500 text-white rounded-md shadow-lg shadow-orange-200">
								Coba Kuis
							</a>
						</div>
					</div>
				</div>
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-xl font-black text-slate-500">
								Diskusi
							</div>
						</div>
					</div>
					<?php
						if ($_GET['sukses'] ?? '0' == 1) :
					?>
						<div class="p-6 bg-teal-50 text-teal-600 text-sm border-b border-teal-200">
							Berhasil menambahkan sebuah data baru!
						</div>
					<?php
						endif;
					?>
					<?php 
						if($_GET['gagal'] ?? '0' == 1):
					?>
					<div class="p-6 bg-red-50 text-red-600 text-sm border-b border-red-200">
						terjadi kesalahan ketika menambahkan data, coba lagi!
					</div>
					<?php 
						endif;
					?>
					
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-green-400">pertanyaan</code>.<code class="text-pink-400">*</code>, <code class="text-pink-400">dukungan_naik</code> - <code class="text-pink-400">dukungan_turun</code> AS <code class="text-pink-400">poin_dukungan</code>,
	<code class="text-green-400">pengguna</code>.<code class="text-pink-400">nama</code> 
	FROM <code class="text-green-400">pertanyaan</code>
	LEFT JOIN <code class="text-green-400">pengguna</code> ON <code class="text-green-400">pengguna</code>.<code class="text-pink-400">id_pengguna</code> = <code class="text-green-400">pertanyaan</code>.<code class="text-pink-400">id_pengguna</code>
	WHERE <code class="text-pink-400">id_video</code> = <code class="text-blue-400"><?=$video->id_video ?></code>;</pre>
						</div>
					</div>
					<ul class="grid grid-cols-1 divide-y divide-solid">
						<?php
						if ($video && $query_pertanyaan->rowCount()) :
							while ($pertanyaan_item = $query_pertanyaan->fetchObject()) :
						?>
								<li class="flex flex-col">
									<div class="p-3 flex h-full hover:bg-slate-50 group gap-6 flex-1 border-b">
										<div class="flex flex-col justify-center items-center">
											<div class="text-sm text-slate-400">
												<?= $pertanyaan_item->poin_dukungan ?>
											</div>
										</div>
										<div>
											<div class="text-xs text-slate-300">
												<?= $pertanyaan_item->tanggal ?? "-" ?> -
												<?= $pertanyaan_item->nama ?? "Tanpa nama" ?>
											</div>
											<div class="text-sm text-slate-400 group-hover:text-slate-500">
												<?= $pertanyaan_item->pertanyaan ?>
											</div>
										</div>
									</div>
									
									<div class="w-full bg-slate-800">
										<div class="mx-auto">
											<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-green-400">balasan</code>.<code class="text-pink-400">*</code>, <code class="text-pink-400">dukungan_naik</code> - <code class="text-pink-400">dukungan_turun</code> AS <code class="text-pink-400">poin_dukungan</code>,
<code class="text-green-400">pengguna</code>.<code class="text-pink-400">nama</code> FROM <code class="text-green-400">balasan</code>
LEFT JOIN <code class="text-green-400">pengguna</code> ON <code class="text-green-400">pengguna</code>.<code class="text-pink-400">id_pengguna</code> = <code class="text-green-400">balasan</code>.<code class="text-pink-400">id_pengguna</code>
WHERE <code class="text-pink-400">id_pertanyaan</code> = <code class="text-blue-400"><?=$pertanyaan_item->id_pertanyaan ?></code>;</pre>
										</div>
									</div>
									<ul>
										<?php
											$query_mengambil_record_balasan = "SELECT balasan.*, dukungan_naik - dukungan_turun AS poin_dukungan,
											pengguna.nama 
											FROM balasan
											LEFT JOIN pengguna ON pengguna.id_pengguna = balasan.id_pengguna
											WHERE id_pertanyaan = :id_pertanyaan";
											$query_balasan = $sambungan->prepare($query_mengambil_record_balasan);
											$query_balasan->execute([
												'id_pertanyaan' => $pertanyaan_item->id_pertanyaan
											]);
											$once = false;
											if ($query_balasan->rowCount()) :
												while ($balasan_item = $query_balasan->fetchObject()) :
											?>
										<li class="flex flex-col ml-10 border-l">
											<div class="p-3 flex h-full hover:bg-slate-50 group gap-6 flex-1 border-b">
												<div class="flex flex-col justify-center items-center">
													<div class="text-sm text-slate-400">
														<?= $balasan_item->poin_dukungan ?>
													</div>
												</div>
												<div>
													<div class="text-xs text-slate-300">
														<?= $balasan_item->tanggal ?? "-" ?> -
														<?= $balasan_item->nama ?? "Tanpa nama" ?>
													</div>
													<div class="text-sm text-slate-400 group-hover:text-slate-500">
														<?= $balasan_item->balasan ?>
													</div>
												</div>
											</div>
										</li>
										<?php
												endwhile;
											endif;
										?>
										<?php 
											if(isset($_SESSION['pengguna_aktif'])):
										?>
										<li class="ml-10 border-l">
											
										<div class="w-full bg-slate-800">
											<div class="mx-auto max-w-2xl">
												<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
INSERT INTO <code class="text-green-400">balasan</code> (
	<code class="text-pink-400">balasan</code>, <code class="text-pink-400">id_pertanyaan</code>, <code class="text-pink-400">id_pengguna</code>, <code class="text-pink-400">tanggal</code>, <code class="text-pink-400">dukungan_naik</code>, <code class="text-pink-400">dukungan_turun</code>
	) VALUE (<code class="text-orange-400">'hi!'</code>, <code class="text-blue-400"><?=$pertanyaan_item->id_pertanyaan ?></code>, <code class="text-blue-400"><?=$_SESSION['pengguna_aktif'] ?></code>, <code class="text-blue-400">NOW()</code>, <code class="text-blue-400">0</code>, <code class="text-blue-400">0</code>);
COMMIT;</pre>
											</div>
										</div>
										<form action="" method="POST">
											<div class="p-2 flex gap-2">
												<div class="w-full flex-grow">
													<input type="hidden" name="id_pertanyaan" value="<?=$pertanyaan_item->id_pertanyaan ?>">
													<textarea name="balasan" id="" rows="1" class="w-full border rounded-md border-slate-300 p-3" placeholder="Balasan"></textarea>
												</div>
												<div>
													<button class="px-6 py-3 rounded-md border bg-teal-500 text-white border-teal-400 text-sm" type="submit">
														Kirim
													</button>
												</div>
											</div>
										</form>
										</li>
										<?php
											endif;
										?>
									</ul>
								</li>
							<?php
						endwhile;
					else :
							?>
							<li class="">
								<div class="p-6 flex h-full justify-between gap-4 w-full">
									<div class="text-sm text-center w-full text-slate-400">
										jadi yang pertama membuka diskusi
									</div>
								</div>
							</li>
						<?php
					endif;
						?>
					</ul>
					<?php 
						if(isset($_SESSION['pengguna_aktif'])):
					?>
						<div class="w-full bg-slate-800">
							<div class="mx-auto max-w-2xl">
								<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
INSERT INTO <code class="text-green-400">pertanyaan</code> (
	<code class="text-pink-400">pertanyaan</code>, <code class="text-pink-400">id_video</code>, <code class="text-pink-400">id_pengguna</code>, <code class="text-pink-400">tanggal</code>, <code class="text-pink-400">dukungan_naik</code>, <code class="text-pink-400">dukungan_turun</code>
	) VALUE (<code class="text-orange-400">'hello world!'</code>, <code class="text-blue-400"><?=$video->id_video ?></code>, <code class="text-blue-400"><?=$_SESSION['pengguna_aktif'] ?></code>, <code class="text-blue-400">NOW()</code>, <code class="text-blue-400">0</code>, <code class="text-blue-400">0</code>);
COMMIT;</pre>
							</div>
						</div>
						<form action="" method="POST">
							<div class="p-6 border-t flex flex-col">
								<div class="w-full">
									<textarea name="pertanyaan" id="" rows="5" class="w-full border rounded-md border-slate-300 p-2"></textarea>
								</div>
								<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400 ml-auto" type="submit">
									Kirim
								</button>
							</div>
						</form>
					<?php 
						else:
					?>
						<div class="p-6 flex border-t">
							<div class="w-full">
								<div class="text-sm text-slate-500 mb-3">
									Masuk untuk mulai diskusi
								</div>
								<div class="w-full">
									<a href="/login" class="px-4 py-3 bg-teal-500 text-white rounded-md flex w-full text-center justify-center">
										Masuk
									</a>
									<div class="text-slate-400">
										atau
									</div>
									<a href="/register" class="px-4 py-3 bg-orange-500 text-white rounded-md flex w-full text-center justify-center">
										Daftar
									</a>
								</div>
							</div>
						</div>
					<?php 
						endif;
					?>
				</div>
			</div>
			<div class="w-full lg:max-w-sm">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-xl font-black text-slate-500">
								<?= $kelas->nama_kelas ?>
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								<?= $kelas->keterangan ?? $kelas->deskripsi_singkat ?>
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">*</code> FROM <code class="text-green-400">video_pembelajaran</code>
	WHERE <code class="text-pink-400">id_kelas</code> = <code class="text-blue-400"><?=$_GET['id_kelas'] ?></code>
	ORDER BY <code class="text-pink-400">urutan</code> ASC, <code class="text-pink-400">tanggal</code> ASC;</pre>
						</div>
					</div>
					<ul class="grid grid-cols-1 divide-y divide-solid">
						<?php
						if ($query_video->rowCount()) :
							$no_urutan = 0;
							while ($video_item = $query_video->fetchObject()) :
								$no_urutan++;
						?>
								<li class="flex">
									<a href="?id_kelas=<?= $_GET['id_kelas'] ?>&id_video=<?= $video_item->id_video ?>" class="p-3 flex h-full <?=($_GET['id_video'] ?? $video->id_video) != $video_item->id_video ? 'hover:bg-slate-50' : 'bg-teal-50 hover:bg-teal-100' ?> group gap-6 flex-1">
										<div class="flex flex-col justify-center items-center">
											<div class="text-lg font-black text-slate-400">
												<?= $no_urutan ?>.
											</div>
										</div>
										<div>
											<div class="text-xs text-slate-300">
												<?= $video_item->tanggal ?? "-" ?>
											</div>
											<div class="text-lg font-black text-slate-400 group-hover:text-slate-500">
												<?= $video_item->judul_video ?>
											</div>
											<div class="text-sm text-slate-300">
												<?= $video_item->keterangan ?? "-" ?>
											</div>
										</div>
										<div class="fill-slate-500 ml-auto">
											<div class="flex h-full justify-center items-center">
												<?php
												if (($_GET['id_video'] ?? $video->id_video) == $video_item->id_video):
												?>
												<svg xmlns="http://www.w3.org/2000/svg" width="1.6rem" height="1.6rem" class="bi bi-pause-circle-fill fill-teal-500" viewBox="0 0 16 16">
													<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z" />
												</svg>
												<?php
												else:
													?>
												<svg xmlns="http://www.w3.org/2000/svg" width="1.6rem" height="1.6rem" class="bi bi-play-circle-fill" viewBox="0 0 16 16">
													<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z" />
												</svg>
												<?php
												endif;
												?>
											</div>
										</div>
									</a>
								</li>
							<?php
						endwhile;
					else :
							?>
							<li class="">
								<div class="p-6 flex h-full justify-between gap-4 w-full">
									<div class="text-xl font-bold text-slate-400">
										Video kosong
									</div>
								</div>
							</li>
						<?php
					endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="w-full flex flex-col justify-center items-center">
			<div class="max-w-lg w-full px-3 mb-4">
				<div class="text-gray-600 text-xl font-bold w-full">
					Daftar Kelas
				</div>
				<div class="text-sm text-gray-400 w-full">
					Daftar kelas pilihan
				</div>
			</div>
			<div class="flex flex-nowrap overflow-x-hidden gap-4 w-full p-4">
				<?php
				if ($query->rowCount()) :
					while ($kelas = $query->fetchObject()) :
				?>
						<a href="/kelas/info-kelas.php?id_kelas=<?= $kelas->id_kelas ?>" class="rounded-3xl border border-slate-50 hover:border-slate-200 overflow-hidden bg-cover bg-center bg-no-repeat max-w-xs w-full flex-shrink-0 flex flex-col" style="background-image: url('<?= $kelas->latar ?>');">
							<div class="h-24 flex justify-center items-center">
								<div class="drop-shadow-lg fill-white p-2 flex justify-center items-center rounded-full bg-teal-500 bg-opacity-30">
									<svg xmlns="http://www.w3.org/2000/svg" width="2.5rem" height="2.5rem" class="bi bi-play-fill" viewBox="0 0 16 16">
										<path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
									</svg>
								</div>
							</div>
							<div class="p-6 bg-gradient-to-t from-slate-50 to-transparent pt-24 text-slate-800 flex-1">
								<h2 class="text-xl font-bold bg-yellow-300 inline-block">
									<?= $kelas->nama_kelas ?>
								</h2>
								<div class="text-sm text-slate-600">
									<?= $kelas->deskripsi_singkat ?>
								</div>
							</div>
						</a>
				<?php
					endwhile;
				endif;
				?>
			</div>
			<div class="max-w-lg w-full px-3 mb-4">
				<a href="/kelas" class="border rounded-lg px-4 py-3 flex font-normal text-slate-600 bg-slate-100">
					<div class="flex font-bold">
						<span>
							Kelas Lainnya
						</span>
					</div>
				</a>
			</div>
		</div>
	</div>
</body>

</html>