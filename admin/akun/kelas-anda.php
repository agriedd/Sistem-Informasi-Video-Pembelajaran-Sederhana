<?php

require_once "../../sambungan.php";

if (!($_SESSION['admin_aktif'] ?? false)) {
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_kelas = "SELECT id_kelas, nama_kelas, deskripsi_singkat, latar, tanggal, admin.nama as nama_pembuat FROM kelas
LEFT JOIN admin ON kelas.id_admin = admin.id_admin
WHERE kelas.id_admin = :id_admin";
$query = $sambungan->prepare($query_mengambil_record_kelas);
$query->execute([
	'id_admin' => $_SESSION['admin_aktif']
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
		$aktif = "kelas-anda";
		require_once('../navbar.php');
		?>

		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Kelas Anda
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Kelola kelas anda sebagai admin
							</div>
						</div>
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("kelas") ?>">
										<a href="/admin/kelas" class="text-sm">
											Seluruh kelas
										</a>
									</li>
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("kelas-anda") ?>">
										<a href="/admin/kelas/kelas-anda.php" class="text-sm">
											Kelas Anda
										</a>
									</li>
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 bg-slate-300">
										<a href="/admin/kelas/tambah-kelas.php" class="text-sm">
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
SELECT <code class="text-pink-400">id_kelas</code>, <code class="text-pink-400">nama_kelas</code>, <code class="text-pink-400">deskripsi_singkat</code>, <code class="text-pink-400">latar</code>, <code class="text-pink-400">tanggal</code>, 
<code class="text-green-400">admin</code>.<code class="text-pink-400">nama</code> as <code class="text-pink-400">nama_pembuat</code> 
FROM <code class="text-pink-400">kelas</code>
	LEFT JOIN <code class="text-green-400">admin</code> ON <code class="text-green-400">kelas</code>.<code class="text-pink-400">id_admin</code> = <code class="text-green-400">admin</code>.<code class="text-pink-400">id_admin</code>
	WHERE <code class="text-green-400">kelas</code>.<code class="text-pink-400">id_admin</code> = <code class="text-blue-400"><?=$_SESSION['admin_aktif'] ?></code>;</pre>
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
								while ($kelas = $query->fetchObject()) :
							?>
									<li class="">
										<a href="/admin/kelas/info-kelas.php?id_kelas=<?=$kelas->id_kelas ?>" class="p-6 flex h-full hover:bg-slate-50 group gap-6">
											<div class="text-2xl font-black text-slate-500 fill-slate-400">
												<img src="<?= $kelas->latar ?>" alt="" class="rounded-lg shadow-md max-h-20 w-full">
											</div>
											<div>
												<div class="text-xs text-slate-300">
													<?= $kelas->tanggal ?? "-" ?>
												</div>
												<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500">
													<?= $kelas->nama_kelas ?>
												</div>
												<div class="text-sm text-slate-300">
													<?= $kelas->deskripsi_singkat ?? "-" ?>
												</div>
												<div class="text-sm text-slate-300">
													dibuat oleh: <?= $kelas->nama_pembuat ?? "-" ?>
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
											Kelas kosong
										</div>
										<a href="/admin/kelas/tambah-kelas.php" class="px-5 py-2 text-sm border rounded-md hover:bg-slate-50 active:bg-slate-100 shadow-sm active:shadow-none">
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