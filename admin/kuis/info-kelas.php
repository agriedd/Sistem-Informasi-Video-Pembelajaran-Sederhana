<?php

require_once "../../sambungan.php";

if (!($_SESSION['admin_aktif'] ?? false)) {
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, latar, tanggal FROM kelas WHERE id_kelas = :id_kelas LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_kelas' => $_GET['id_kelas']
]);
$kelas = $query->fetchObject();

$query_mengambil_record_quiz = "SELECT 
	a.id_video, 
	a.judul_video, 
	a.keterangan, 
	a.video,
	a.tanggal
	 FROM video_pembelajaran as a
	WHERE a.id_kelas = :id_kelas
ORDER BY a.urutan ASC, a.tanggal ASC";
$query = $sambungan->prepare($query_mengambil_record_quiz);
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
					<div class="p-6 flex justify-between flex-wrap gap-4 bg-cover bg-center bg-no-repeat h-32 rounded-t-md" style="background-image: url('<?= $kelas->latar ?>');">
					</div>
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Info Kelas
							</div>
							<div class="text-4xl font-black text-slate-500">
								Kuis pada: <?= $kelas->nama_kelas ?>
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Kelola kelas sebagai admin
							</div>
						</div>
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">

									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400">
										<a href="/admin/kuis" class="text-sm">
											Kembali
										</a>
									</li>
								</ul>
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
					<div class="">
						<ul class="grid grid-cols-1 divide-y divide-solid">
							<?php
							if ($query->rowCount()) :
								$no_urutan = 0;
								while ($video = $query->fetchObject()) :
									$no_urutan++;
							?>
									<li class="flex">
										<a href="/admin/kuis/info-video.php?id_video=<?= $video->id_video ?>" class="p-6 flex h-full hover:bg-slate-50 group gap-6 flex-1">
											<!-- <div class="text-2xl font-black text-slate-500 fill-slate-400">
												<img src="<?=$video->video ?>" class="rounded-lg shadow-md max-h-20 w-full"/>
											</div> -->
											<div>
												<div class="text-xs text-slate-300">
													<?= $video->tanggal ?? "-" ?>
												</div>
												<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500">
													<?= $video->judul_video ?>
												</div>
												<div class="text-sm text-slate-300">
													<?= $video->keterangan ?? "-" ?>
												</div>
											</div>
											<div class="fill-slate-500 ml-auto">
												<div class="flex h-full justify-center items-center">
													<svg xmlns="http://www.w3.org/2000/svg" width="1.6rem" height="1.6rem" class="bi bi-arrow-right" viewBox="0 0 16 16">
														<path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
													</svg>
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
										<a href="/admin/kelas/tambah-video.php?id_kelas=<?= $kelas->id_kelas ?>" class="px-5 py-2 text-sm border rounded-md hover:bg-slate-50 active:bg-slate-100 shadow-sm active:shadow-none">
											Tambah
										</a>
									</div>
								</li>
							<?php
							endif;
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>