<?php

require_once "../../sambungan.php";

if (!($_SESSION['admin_aktif'] ?? false)) {
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_video = "SELECT id_video, judul_video, keterangan, video, id_kelas, tanggal FROM video_pembelajaran WHERE id_video = :id_video LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_video);
$query->execute([
	'id_video' => $_GET['id_video']
]);
$video = $query->fetchObject();

$query_mengambil_record_quiz = "SELECT * FROM kuis WHERE id_video = :id_video";
$query = $sambungan->prepare($query_mengambil_record_quiz);
$query->execute([
	'id_video' => $video->id_video
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
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Info video
							</div>
							<div class="text-4xl font-black text-slate-500">
								Kuis pada: <?= $video->judul_video ?>
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Kelola kuis sebagai admin
							</div>
						</div>
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400">
										<a href="/admin/kuis/info-kelas.php?id_kelas=<?=$video->id_kelas ?>" class="text-sm">
											Kembali
										</a>
									</li>
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 bg-slate-300">
										<a href="/admin/kuis/tambah-kuis.php?id_video=<?= $video->id_video ?>" class="text-sm">
											Tambah
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">*</code> FROM <code class="text-green-400">kuis</code> WHERE <code class="text-pink-400">id_video</code> = <code class="text-blue-400"><?=$_GET['id_video'] ?></code>;</pre>
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
								while ($kuis = $query->fetchObject()) :
									$no_urutan++;
							?>
									<li class="flex">
										<a href="/admin/kuis/info-kuis.php?id_kuis=<?= $kuis->id_kuis ?>" class="p-6 flex h-full hover:bg-slate-50 group gap-6 flex-1">
											<div class="text-xl font-black text-slate-500 fill-slate-400">
												<?=$no_urutan ?>.
											</div>
											<div>
												<div class="text-xl font-black text-slate-400 group-hover:text-slate-500">
													<?= $kuis->pertanyaan_kuis ?>
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
										<div class="">
											<a href="./hapus-kuis.php?id_kuis=<?=$kuis->id_kuis ?>&id_video=<?=$_GET['id_video'] ?>" title="Hapus" class="flex p-6 bg-red-100 fill-red-600 h-full justify-center items-center
												hover:fill-white hover:bg-red-600 transition-all
												border-l border-red-300">
												<svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" class="bi bi-x" viewBox="0 0 16 16">
													<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
												</svg>
											</a>
										</div>
									</li>
								<?php
								endwhile;
							else :
								?>
								<li class="">
									<div class="p-6 flex h-full justify-between gap-4 w-full">
										<div class="text-xl font-bold text-slate-400">
											Kuis kosong
										</div>
										<a href="/admin/kuis/tambah-kuis.php?id_video=<?= $video->id_video ?>" class="px-5 py-2 text-sm border rounded-md hover:bg-slate-50 active:bg-slate-100 shadow-sm active:shadow-none">
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