<?php

require_once "../sambungan.php";


$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, keterangan, latar, tanggal FROM kelas WHERE id_kelas = :id_kelas LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);
$kelas = $query->fetchObject();

$query_mengambil_record_video_pertama = "SELECT * FROM video_pembelajaran
WHERE id_kelas = :id_kelas
ORDER BY urutan ASC, tanggal ASC LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_video_pertama);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);
$video = $query->fetchObject();

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, latar, tanggal FROM kelas
WHERE id_kelas <> :id_kelas
ORDER BY tanggal DESC";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);

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
		.bg-pattern{
			background-color: rgba(244,244,255,0);
			/* opacity: 0.6; */
			background-image:  linear-gradient(#dbddff 1.2000000000000002px, transparent 1.2000000000000002px), linear-gradient(to right, #dbddff 1.2000000000000002px, rgba(244,244,255,0) 1.2000000000000002px);
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
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								<?=$kelas->nama_kelas ?>
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								<?=$kelas->keterangan ?? $kelas->deskripsi_singkat ?>
							</div>
						</div>
					</div>
					<div class="flex justify-center">
						<div>
							<video src="<?=$video->video ?>" controls></video>
						</div>
					</div>
				</div>
			</div>
			<div class="w-full max-w-sm">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								<?=$kelas->nama_kelas ?>
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								<?=$kelas->keterangan ?? $kelas->deskripsi_singkat ?>
							</div>
						</div>
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
				<a href="/kelas/info-kelas.php?id_kelas=<?=$kelas->id_kelas ?>" class="rounded-3xl border border-slate-50 hover:border-slate-200 overflow-hidden bg-cover bg-center bg-no-repeat max-w-xs w-full flex-shrink-0 flex flex-col" style="background-image: url('<?=$kelas->latar ?>');">
					<div class="h-24 flex justify-center items-center">
						<div class="drop-shadow-lg fill-white p-2 flex justify-center items-center rounded-full bg-teal-500 bg-opacity-30">
							<svg xmlns="http://www.w3.org/2000/svg" width="2.5rem" height="2.5rem" class="bi bi-play-fill" viewBox="0 0 16 16">
								<path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
							</svg>
						</div>
					</div>
					<div class="p-6 bg-gradient-to-t from-slate-50 to-transparent pt-24 text-slate-800 flex-1">
						<h2 class="text-xl font-bold bg-yellow-300 inline-block">
							<?=$kelas->nama_kelas ?>
						</h2>
						<div class="text-sm text-slate-600">
							<?=$kelas->deskripsi_singkat ?>
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