<?php

require_once "../../sambungan.php";

if(!($_SESSION['admin_aktif'] ?? false)){
	header("Location: ../../login/admin");
	exit();
}

$sambungan->query("START TRANSACTION");

try {
	$query_tambah_admin = "DELETE FROM admin WHERE id_admin=:id_admin LIMIT 1";
	$query = $sambungan->prepare($query_tambah_admin);
	$hasil = $query->execute([
		'id_admin' => $_GET['id_admin'],
	]);
	if(!$hasil) throw new Error("Gagal menghapus data!");

	$sambungan->query("COMMIT");
} catch (\Throwable $th) {
	$hasil = false;
	$sambungan->query("ROLLBACK");
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
		$aktif = "admin";
		require_once('../navbar.php');
		?>
		
		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Pemberitahuan
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
DELETE FROM <code class="text-green-400">admin</code> WHERE <code class="text-pink-400">id_admin</code>=<code class="text-blue-400"><?=$_GET['id_admin'] ?></code> LIMIT 1;
COMMIT;</pre>
						</div>
					</div>
					<?php 
						if(!$hasil):
					?>
					<div class="p-6 bg-red-600 text-red-50 text-sm border-b border-red-500">
						terjadi kesalahan ketika menghapus data, coba lagi!
					</div>
					<?php 
						else:
					?>
					<div class="p-6 bg-teal-600 text-teal-50 text-sm border-b border-teal-500">
						berhasi menghapus data!
					</div>
					<?php 
						endif;
					?>
					<div class="p-6 flex">
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400">
										<a href="/admin/akun" class="text-sm">
											Kembali
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>