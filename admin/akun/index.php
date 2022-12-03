<?php

require_once "../../sambungan.php";

if (!($_SESSION['admin_aktif'] ?? false)) {
	header("Location: ../../login/admin");
	exit();
}

$query_mengambil_record_admin = "SELECT id_admin, nama, email, jenis_kelamin, tanggal_lahir FROM admin";
$query = $sambungan->prepare($query_mengambil_record_admin);
$query->execute([]);

$query_mengambil_record_pengguna = "SELECT id_pengguna, nama, email, jenis_kelamin FROM pengguna";
$query_pengguna = $sambungan->prepare($query_mengambil_record_pengguna);
$query_pengguna->execute([]);

$query_mengambil_jumlah_pengguna = "SELECT COUNT(*) as jumlah FROM pengguna";
$query_jumlah_pengguna = $sambungan->prepare($query_mengambil_jumlah_pengguna);
$query_jumlah_pengguna->execute([]);
$jumlah_pengguna = $query_jumlah_pengguna->fetchObject()->jumlah;


if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sambungan->query("START TRANSACTION");
	
	try {
		$query_tambah_admin = "INSERT INTO admin (nama, email, jenis_kelamin, tanggal_lahir, kata_sandi) VALUE (:nama, :email, :jenis_kelamin, :tanggal_lahir, MD5(:kata_sandi))";
		$query = $sambungan->prepare($query_tambah_admin);
		$hasil = $query->execute($_POST);
		if(!$hasil) throw new Error("Gagal menambahkan data!");
		$sambungan->query("COMMIT");
		header("location: akun?sukses=1");
		exit();
	} catch (\Throwable $th) {
		$sambungan->query("ROLLBACK");
		header("location: akun?gagal=1&pesan={$th->getMessage()}");
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
		$aktif = "akun";
		require_once('../navbar.php');
		?>

		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-4xl grid grid-cols-1 gap-4">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Akun
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Kelola akun sebagai admin
							</div>
						</div>
						<div>
							<div class="p-1 rounded-full bg-slate-100">
								<ul class="flex gap-1">
									<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("akun") ?>">
										<a href="/admin/akun" class="text-sm">
											Seluruh admin
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">id_admin</code>, <code class="text-pink-400">nama</code>, 
<code class="text-pink-400">email</code>, <code class="text-pink-400">jenis_kelamin</code>, <code class="text-pink-400">tanggal_lahir</code> 
  FROM <code class="text-green-400">admin</code>;</pre>
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
					<form action="" method="POST" enctype="multipart/form-data" class="w-full">
						<table class="w-full border-collapse">
							<thead>
								<tr class="bg-slate-50">
									<th class="p-3 text-sm font-normal border">
										No.
									</th>
									<th class="p-3 text-sm font-normal border">
										Nama
									</th>
									<th class="p-3 text-sm font-normal border">
										Email
									</th>
									<th class="p-3 text-sm font-normal border">
										Jenis Kelamin
									</th>
									<th class="p-3 text-sm font-normal border">
										Tanggal Lahir
									</th>
									<th class="p-3 text-sm font-normal border">
										Kata Sandi
									</th>
									<th class="p-3 text-sm font-normal border">
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($query->rowCount()) :
									$no = 0;
									while ($admin = $query->fetchObject()) :
										$no++;
								?>
								<tr>
									<td class="p-2 text-sm text-slate-600">
										<?= $no ?>.
									</td>
									<td class="p-2 text-sm text-slate-600">
										<?= $admin->nama ?>
									</td>
									<td class="p-2 text-sm text-slate-600">
										<?= $admin->email ?>
									</td>
									<td class="p-2 text-sm text-slate-600">
										<?= $admin->jenis_kelamin == 'l' ? 'Laki-laki' : 'Perempuan' ?>
									</td>
									<td class="p-2 text-sm text-slate-600">
										<?= $admin->tanggal_lahir ?>
									</td>
									<td class="p-2 text-sm text-slate-600">
										***
									</td>
									<td class="text-sm text-slate-600">
										<div class="">
											<a href="akun/hapus-admin.php?id_admin=<?=$admin->id_admin ?>" title="Hapus" class="flex p-6 bg-red-100 fill-red-600 h-full justify-center items-center
												hover:fill-white hover:bg-red-600 transition-all
												border-l border-red-300">
												<svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem" class="bi bi-x" viewBox="0 0 16 16">
													<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
												</svg>
											</a>
										</div>
									</td>
								</tr>
									<?php
									endwhile;
								else :
									?>
									<tr>
										<td class="" colspan="7">
											<div class="p-6 flex h-full justify-between gap-4 w-full">
												<div class="text-xl font-bold text-slate-400">
													Admin kosong
												</div>
											</div>
										</td>
									</tr>
								<?php
								endif;
								?>
								<?php 
									if($_GET['gagal'] ?? '0' == 1):
								?>
								<tr>
									<td class="" colspan="7">
										<div class="p-6 bg-red-50 text-red-600 text-sm border-b border-red-200">
											Gagal menambahkan data pastikan anda tidak pernah menggunakan email yang sama sebelumnya
										</div>
									</td>
								</tr>
								<?php 
									endif;
								?>
								<tr>
									<td class="" colspan="7">
										<div class="w-full bg-slate-800">
											<div class="mx-auto max-w-2xl">
												<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
INSERT INTO <code class="text-green-400">admin</code> 
	(<code class="text-pink-400">nama</code>, <code class="text-pink-400">jenis_kelamin</code>, <code class="text-pink-400">tanggal_lahir</code>, <code class="text-pink-400">email</code>, <code class="text-pink-400">kata_sandi</code>) 
	VALUE 
	(<code class="text-orange-400">'Edd'</code>, <code class="text-orange-400">'l'</code>, <code class="text-orange-400">'2020-01-01'</code>, <code class="text-orange-400">'adminbaru@gmail.com'</code>, <code class="text-blue-400">MD5</code>(<code class="text-orange-400">'password'</code>));
COMMIT;</pre>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="p-2 text-sm border text-slate-600">
										<?= ++$no ?>.
									</td>
									<td class="p-2 text-sm border text-slate-600">
										<input type="text" id="nama" name="nama" class="px-3 py-2 border rounded-md w-full" placeholder="Nama Admin" value="">
									</td>
									<td class="p-2 text-sm border text-slate-600">
										<input type="text" id="email" name="email" class="px-3 py-2 border rounded-md w-full" placeholder="Email" value="">
									</td>
									<td class="p-2 text-sm border text-slate-600">
										<select id="jenis_kelamin" name="jenis_kelamin" class="px-3 py-2 border rounded-md w-full">
											<option value="l">Laki-Laki</option>
											<option value="p">Perempuan</option>
										</select>
									</td>
									<td class="p-2 text-sm border text-slate-600">
										<input type="date" id="tanggal_lahir" name="tanggal_lahir" class="px-3 py-2 border rounded-md w-full" value="2020-01-01">
									</td>
									<td class="p-2 text-sm border text-slate-600">
										<input type="password" id="kata_sandi" name="kata_sandi" class="px-3 py-2 border rounded-md w-full" placeholder="Kata Sandi" value="password">
									</td>
									<td class="text-sm border text-slate-600">
										<div class="w-full">
											<button title="Tambah" type="submit" class="flex p-6 bg-teal-100 text-teal-600 h-full w-full justify-center items-center
												hover:text-white hover:bg-teal-600 transition-all
												border-l border-teal-300">
												Tambah
											</button>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b justify-between flex-wrap gap-4">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Pengguna
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Kelola pengguna sebagai admin
							</div>
						</div>
						<div>
							<div class="text-4xl font-black text-slate-500 text-right">
								<?=$jumlah_pengguna ?>
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-2xl">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">id_pengguna</code>, <code class="text-pink-400">nama</code>, <code class="text-pink-400">email</code>, <code class="text-pink-400">jenis_kelamin</code> 
  FROM <code class="text-green-400">pengguna</code>;</pre>
						</div>
					</div>
					<div class="">
						<ul class="grid grid-cols-1 divide-y divide-solid">
							<?php
							if ($query->rowCount()) :
								while ($pengguna = $query_pengguna->fetchObject()) :
							?>
									<li class="flex">
										<div class="p-6 flex h-full hover:bg-slate-50 group gap-6 flex-grow">
											<div>
												<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500">
													<?= $pengguna->nama ?>
												</div>
												<div class="text-sm text-slate-400">
													<?= $pengguna->email ?? "-" ?>
												</div>
												<div class="text-sm text-slate-400">
													<?= $pengguna->jenis_kelamin == 'l' ? "Laki-laki" : "Perempuan" ?>
												</div>
											</div>
										</div>
										
										<div class="">
											<a href="akun/hapus-pengguna.php?id_pengguna=<?=$pengguna->id_pengguna ?>" title="Hapus" class="flex p-6 bg-red-100 fill-red-600 h-full justify-center items-center
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
											Pengguna kosong
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
		</div>
	</div>
</body>

</html>