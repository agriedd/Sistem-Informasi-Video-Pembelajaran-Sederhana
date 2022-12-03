<?php

require_once "../sambungan.php";

if (!($_SESSION['admin_aktif'] ?? false)) {
	header("Location: ../login");
	exit();
}

$query_mengambil_record_video = "SELECT * FROM video_pembelajaran
WHERE id_video = :id_video
ORDER BY urutan ASC, tanggal ASC LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_video);
$query->execute([
	'id_video' => $_GET['id_video']
]);
$video = $query->fetchObject();


if($_SERVER['REQUEST_METHOD'] == "POST"){


	$sambungan->query("START TRANSACTION");
	
	try {

		$query_mengambil_record_kuis = "SELECT *
			FROM kuis
			WHERE id_video = :id_video";
		$query_kuis = $sambungan->prepare($query_mengambil_record_kuis);
		$query_kuis->execute([
			'id_video' => $video->id_video
		]);
		

		$benar = [];
		$pertanyaan = [];

		while($kuis = $query_kuis->fetchObject()){
			
			$pertanyaan[] = $kuis;

			$list_jawaban = $_POST["{$kuis->id_kuis}"] ?? [];
	
			$query_mengambil_record_jawaban = "SELECT id_opsi_jawaban, status_benar
				FROM opsi_jawaban
				WHERE id_kuis = :id_kuis AND
				status_benar = 'benar'";
			$query_opsi = $sambungan->prepare($query_mengambil_record_jawaban);
			
			$query_opsi->execute([
				'id_kuis' => $kuis->id_kuis
			]);
			$jawaban_benar = [];
			while($opsi_benar = $query_opsi->fetchObject()){
				$jawaban_benar[] = $opsi_benar->id_opsi_jawaban;
			}
			if($query_opsi->rowCount() == count($list_jawaban) && array_diff($list_jawaban, $jawaban_benar) == array_diff($jawaban_benar, $list_jawaban)){
				$benar[] = true;
			}
		}
		
		$skor = (count($benar)/count($pertanyaan)) * 100;

		$query_tambah_kuis = "INSERT INTO kuis_pengguna (id_video, id_pengguna, tanggal, skor) VALUE (:id_video, :id_pengguna, NOW(), :skor)";
		$query = $sambungan->prepare($query_tambah_kuis);
		$hasil = $query->execute([
			'id_video' => $video->id_video,
			'id_pengguna' => $_SESSION['pengguna_aktif'],
			'skor' => $skor
		]);
		if(!$hasil) throw new Error("Gagal menambahkan pertanyaan!");
		$sambungan->query("COMMIT");
		header("location: ./kuis.php?id_video={$_GET['id_video']}&sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./kuis.php?id_video={$_GET['id_video']}&gagal=1&pesan={$th->getMessage()}");
		exit();
	}
		
}

if($video){
	$query_mengambil_record_kuis = "SELECT *
	FROM kuis
	WHERE id_video = :id_video
	ORDER BY RAND()";
	$query_kuis = $sambungan->prepare($query_mengambil_record_kuis);
	$query_kuis->execute([
		'id_video' => $video->id_video
	]);
	$query_mengambil_record_skor_kuis_tertinggi = "SELECT *
	FROM kuis_pengguna
	WHERE id_video = :id_video AND id_pengguna = :id_pengguna
	ORDER BY skor DESC, tanggal DESC LIMIT 3";
	$query_kuis_pengguna = $sambungan->prepare($query_mengambil_record_skor_kuis_tertinggi);
	$query_kuis_pengguna->execute([
		'id_video' => $video->id_video,
		'id_pengguna' => $_SESSION['pengguna_aktif'],
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
					<?php
						if($video):
					?>
					<div class="p-6 flex gap-3">
						<div class="">
							<div class="text-3xl font-black text-slate-500 mb-3">
								[Kuis] <?= $video->judul_video ?>
							</div>
							<div class="text-slate-500 group-hover:text-slate-500 max-w-sm">
								<?= $video->keterangan ?>
							</div>
						</div>
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">

									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400">
										<a href="/kelas/info-kelas.php?id_kelas=<?= $video->id_kelas ?>&id_video=<?= $video->id_video ?>" class="text-sm">
											Kembali
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">*</code>
	FROM <code class="text-green-400">kuis_pengguna</code>
	WHERE <code class="text-pink-400">id_video</code> = <code class="text-blue-400"><?=$_GET['id_video'] ?></code> AND <code class="text-pink-400">id_pengguna</code> = <code class="text-blue-400"><?=$_SESSION['pengguna_aktif'] ?></code>
	ORDER BY <code class="text-pink-400">skor</code> DESC, <code class="text-pink-400">tanggal</code> DESC LIMIT 3;</pre>
						</div>
					</div>
					<div class="mb-6">
						<table class="border-collapse w-full">
							<thead>
								<tr class="bg-slate-100">
									<td class="p-2 text-sm text-slate-800 border">
										No.
									</td>
									<td class="p-2 text-sm text-slate-800 border">
										Skor
									</td>
									<td class="p-2 text-sm text-slate-800 border">
										Tanggal
									</td>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($video && $query_kuis_pengguna->rowCount()) :
										$no = 0;
										while ($kuis_item = $query_kuis_pengguna->fetchObject()) :
											$no++;
								?>
								<tr>
									<td class="p-2 text-sm text-slate-800 border">
										<?= $no ?>.
									</td>
									<td class="p-2 text-sm text-slate-800 border">
										<?= $kuis_item->skor ?>
									</td>
									<td class="p-2 text-sm text-slate-800 border">
										<?= $kuis_item->tanggal ?>
									</td>
								</tr>
								<?php
										endwhile;
									else:
								?>
								<?php
									endif;
								?>
							</tbody>
						</table>
					</div>
					<div class="flex justify-center">
						<form action="" method="POST" enctype="multipart/form-data" class="w-full">
							<table class="border-collapse w-full">
								<tbody>
									<?php
										if ($video && $query_kuis->rowCount()) :
											$no = 0;
											while ($kuis_item = $query_kuis->fetchObject()) :
												$no++;
												$query_mengambil_record_opsi = "SELECT *
												FROM opsi_jawaban
												WHERE id_kuis = :id_kuis
												ORDER BY RAND()";
												$query_opsi = $sambungan->prepare($query_mengambil_record_opsi);
												$query_opsi->execute([
													'id_kuis' => $kuis_item->id_kuis
												]);
									?>
									<tr>
										<td class="p-2 text-sm text-slate-800 border">
											<?= $no ?>.
										</td>
										<td colspan="3" class="p-2 text-sm text-slate-800 border">
											<?= $kuis_item->pertanyaan_kuis ?>
										</td>
									</tr>
									<?php
												if ($query_opsi->rowCount()) :
													$opsi_no = 96;
													while ($opsi = $query_opsi->fetchObject()) :
														$opsi_no++;
									?>
													<tr>
														<td class="p-2 text-sm text-slate-800 border">
														</td>
														<td class="p-2 text-sm text-slate-800 border">
															<div class="h-full flex justify-center items-center">
																<input type="checkbox" id="<?=$kuis_item->id_kuis ?>" name="<?=$kuis_item->id_kuis ?>[]" class="accent-blue-500 h-4 w-4 cursor-pointer" value="<?=$opsi->id_opsi_jawaban ?>">
															</div>
														</td>
														<td class="p-2 text-sm text-slate-800 border" colspan="2">
															<?= chr($opsi_no) ?>.
															<?= $opsi->jawaban ?>
														</td>
													</tr>
									<?php
													endwhile;
												endif;
											endwhile;
										else:
									?>
									<tr>
										<td class="p-2 text-sm text-slate-800 border">
											Tidak ada kuis
										</td>
									</tr>
									<?php
										endif;
									?>
								</tbody>
							</table>
							<div class="w-full bg-slate-800">
								<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
INSERT INTO <code class="text-green-400">kuis_pengguna</code> 
	(<code class="text-pink-400">id_video</code>, <code class="text-pink-400">id_pengguna</code>, <code class="text-pink-400">tanggal</code>, <code class="text-pink-400">skor</code>) 
	VALUE (<code class="text-blue-400"><?=$_GET['id_video'] ?></code>, <code class="text-pink-400"><?=$_SESSION['pengguna_aktif'] ?></code>, <code class="text-blue-400">NOW</code>(), <code class="text-blue-400">100.00</code>);
COMMIT;</pre>
								</div>
							</div>
							<div class="p-6">
								<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400" type="submit">
									Submit
								</button>
							</div>
						</form>
					</div>
					<?php
						endif;
					?>
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