<?php

require_once "../sambungan.php";

$query_mengambil_record_skor_kuis_tertinggi = "SELECT kuis_pengguna.*, video_pembelajaran.id_kelas, video_pembelajaran.judul_video
FROM kuis_pengguna
LEFT JOIN video_pembelajaran 
	ON video_pembelajaran.id_video = kuis_pengguna.id_video
WHERE kuis_pengguna.id_pengguna = :id_pengguna
ORDER BY kuis_pengguna.skor DESC, kuis_pengguna.tanggal DESC";
$query_kuis_pengguna = $sambungan->prepare($query_mengambil_record_skor_kuis_tertinggi);
$query_kuis_pengguna->execute([
	'id_pengguna' => $_SESSION['pengguna_aktif'],
]);

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, latar, tanggal FROM kelas ORDER BY tanggal DESC";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([]);

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
		$aktif = "dasbor";
		require_once('../navbar.php');
		?>

		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap lg:flex-wrap-reverse">
			<div class="w-full max-w-2xl grid lg:grid-cols-1 gap-4">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex gap-3">
						<div class="">
							<div class="text-3xl font-black text-slate-500 mb-3">
								Daftar Kuis Anda
							</div>
							<div class="text-slate-500 group-hover:text-slate-500 max-w-sm">
								Daftar Kuis Anda
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">kuis_pengguna</code>.<code class="text-pink-400">*</code>, 
	<code class="text-green-400">video_pembelajaran</code>.<code class="text-pink-400">id_kelas</code>, <code class="text-green-400">video_pembelajaran</code>.<code class="text-pink-400">judul_video</code>
FROM <code class="text-green-400">kuis_pengguna</code>
LEFT JOIN <code class="text-green-400">video_pembelajaran</code> 
	ON <code class="text-green-400">video_pembelajaran</code>.<code class="text-pink-400">id_video</code> = <code class="text-green-400">kuis_pengguna</code>.<code class="text-pink-400">id_video</code>
WHERE <code class="text-green-400">kuis_pengguna</code>.<code class="text-pink-400">id_pengguna</code> = <code class="text-blue-300"><?=$_SESSION['pengguna_aktif'] ?></code>
ORDER BY <code class="text-green-400">kuis_pengguna</code>.<code class="text-pink-400">skor</code> DESC, <code class="text-green-400">kuis_pengguna</code>.<code class="text-pink-400">tanggal</code> DESC;</pre>
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
									<td class="p-2 text-sm text-slate-800 border">
										Video
									</td>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($query_kuis_pengguna->rowCount()) :
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
									<td class="text-sm text-slate-800 border">
										<a href="/kelas/info-kelas.php?id_kelas=<?=$kuis_item->id_kelas ?>&id_video=<?=$kuis_item->id_video ?>" class="flex p-4 bg-slate-200 text-slate-700 hover:bg-slate-300 hover:text-slate-800">
											<?= $kuis_item->judul_video ?>
										</a>
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