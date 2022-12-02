<?php

require_once "../../sambungan.php";

if (!($_SESSION['admin_aktif'] ?? false)) {
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_kuis = "SELECT * FROM kuis WHERE id_kuis = :id_kuis LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_kuis);
$query->execute([
	'id_kuis' => $_GET['id_kuis']
]);
$kuis = $query->fetchObject();

$query_mengambil_record_opsi_jawaban = "SELECT * FROM opsi_jawaban WHERE id_kuis = :id_kuis ORDER BY RAND()";
$query = $sambungan->prepare($query_mengambil_record_opsi_jawaban);
$query->execute([
	'id_kuis' => $_GET['id_kuis']
]);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$sambungan->query("START TRANSACTION");

	try {
		$query_tambah_opsi_jawaban = "INSERT INTO opsi_jawaban (jawaban, status_benar, id_kuis) VALUE (:jawaban, :status_benar, :id_kuis)";
		$query = $sambungan->prepare($query_tambah_opsi_jawaban);
		$hasil = $query->execute([
			'jawaban' => $_POST['jawaban'],
			'id_kuis' => $kuis->id_kuis,
			'status_benar' => $_POST['status_benar'] ? 'benar' : 'salah'
		]);
		if (!$hasil) throw new Error("Gagal menambahkan data!");

		$sambungan->query("COMMIT");
		header("location: ./info-kuis.php?id_kuis={$kuis->id_kuis}&sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: ./info-kuis.php?id_kuis={$kuis->id_kuis}&gagal=1&pesan={$th->getMessage()}");
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
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Info Kuis
							</div>
							<div class="text-4xl font-black text-slate-500">
								<?= $kuis->pertanyaan_kuis ?>
							</div>
						</div>
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400">
										<a href="/admin/kuis/info-video.php?id_video=<?= $kuis->id_video ?>" class="text-sm">
											Kembali
										</a>
									</li>
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 bg-slate-300">
										<a href="/admin/kuis/ubah-kuis.php?id_kuis=<?= $kuis->id_kuis ?>" class="text-sm">
											ubah
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">*</code> FROM <code class="text-green-400">opsi_jawaban</code> WHERE <code class="text-pink-400">id_kuis</code> = <code class="text-blue-400"><?= $_GET['id_kuis'] ?></code> ORDER BY <code class="text-blue-400">RAND()</code>;</pre>
						</div>
					</div>
					<?php
					if ($_GET['sukses'] ?? '0' == 1) :
					?>
						<div class="p-6 bg-teal-50 text-teal-600 text-sm border-b border-teal-200">
							Berhasil menyimpan perubahan!
						</div>
					<?php
					endif;
					?>
					<div>
						<ul class="grid grid-cols-1 divide-y divide-solid">
							<?php
							if ($query->rowCount()) :
								$no_urutan = 0;
								while ($jawaban = $query->fetchObject()) :
									$no_urutan++;
							?>
									<li class="flex">
										<div class="p-6 flex h-full  group gap-6 flex-1 <?= $jawaban->status_benar == 'benar' ? 'bg-teal-50 hover:bg-teal-100 text-teal-400 group-hover:text-teal-500' : 'hover:bg-slate-50 text-slate-400 group-hover:text-slate-500' ?>">
											<div>
												<div class="text-sm h-full flex justify-center flex-col">
													<?= $no_urutan ?>.
												</div>
											</div>
											<div>
												<div class="text-xl font-black">
													<?= $jawaban->jawaban ?>
												</div>
											</div>
											<div class="fill-teal-500 ml-auto">
												<?php
												if ($jawaban->status_benar == 'benar') :
												?>
													<div class="flex h-full justify-center items-center">
														<svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" class="bi bi-check-square-fill" viewBox="0 0 16 16">
															<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z" />
														</svg>
													</div>
												<?php
												endif;
												?>
											</div>
										</div>
										<div class="">
											<a href="./ubah-opsi-jawaban.php?id_opsi_jawaban=<?= $jawaban->id_opsi_jawaban ?>" title="Ubah" class="flex p-6 bg-gray-100 fill-gray-600 h-full justify-center items-center
												hover:fill-white hover:bg-gray-600 transition-all
												border-l border-gray-300">
												<svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" class="bi bi-pencil" viewBox="0 0 16 16">
													<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
												</svg>
											</a>
										</div>
										<div class="">
											<a href="./hapus-opsi-jawaban.php?id_kuis=<?= $_GET['id_kuis'] ?>&id_opsi_jawaban=<?= $jawaban->id_opsi_jawaban ?>" title="Hapus" class="flex p-6 bg-red-100 fill-red-600 h-full justify-center items-center
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
												<?= ++$no_urutan ?>.
											</div>
											<input type="text" id="jawaban" name="jawaban" class="px-3 py-2 border rounded-md w-full" placeholder="Opsi Jawaban">
											<div class="w-11 flex justify-center">
												<input type="checkbox" id="status_benar" name="status_benar" class="accent-blue-500 h-4 w-4 cursor-pointer" value="<?= $no_urutan ?>">
											</div>
										</div>
									</div>
									<div class="pt-6">
										<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400" type="submit">
											Tambah
										</button>
									</div>
								</div>
							</form>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>