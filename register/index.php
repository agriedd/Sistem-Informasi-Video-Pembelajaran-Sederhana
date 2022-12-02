<?php

	require_once "../sambungan.php";

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		try {

			$sambungan->query("START TRANSACTION");

			$query_menambah_pengguna = "INSERT INTO pengguna (nama, jenis_kelamin, tanggal_lahir, email, kata_sandi) VALUE (:nama, :jenis_kelamin, :tanggal_lahir, :email, MD5(:kata_sandi))";
			$query = $sambungan->prepare($query_menambah_pengguna);
			$hasil = $query->execute($_POST);

			/**
			 * jika record yang didapat melebihi 0 atau tidak 0
			 */
			if($hasil){
				
				$query_mengambil_pengguna = "SELECT id_pengguna, email FROM pengguna 
				WHERE email = :email LIMIT 1";
				$query = $sambungan->prepare($query_mengambil_pengguna);
				$hasil = $query->execute([
					'email' => $_POST['email']
				]);

				/**
				 * jika record yang didapat melebihi 0 atau tidak 0
				 */
				if($query->rowCount() > 0){

					$admin = $query->fetchObject();
					
					$_SESSION['pengguna_aktif'] = $pengguna->id_pengguna;
					$sambungan->query("COMMIT");

					header("location: ../pengguna");
					exit();
				} else {
					throw new Error("gagal menambahkan data!");
				}
			} else {
				$sambungan->query("ROLLBACK");
				header("location: ./register?gagal=1");
				exit();
			}
		} catch (\Throwable $th) {
			$sambungan->query("ROLLBACK");
			header("location: ./register?gagal=1");
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
			$aktif = "register";
			require_once('../navbar.php');
		?>

		<div class="w-full border-b flex justify-center py-4 bg-pattern">
			<div class="w-full md:max-w-xl max-w-sm">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-3 flex border-b">
						<div class="p-1 rounded-full bg-slate-100">
							<ul class="flex gap-1">
								<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("masuk") ?>">
									<a href="/login" class="text-sm">
										Login
									</a>
								</li>
								<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("register") ?>">
									<a href="/register" class="text-sm">
										Daftar/Register
									</a>
								</li>
							</ul>
						</div>
					</div>
					<?php 
						if($_GET['gagal'] ?? '0' == 1):
					?>
					<div class="p-6 bg-red-50 text-red-600 text-sm border-b border-red-200">
						Gagal mendaftar pastikan anda tidak pernah menggunakan email yang sama sebelumnya
					</div>
					<?php 
						endif;
					?>
					<form action="" method="POST">
						<div class="p-6">
							<div class="text-4xl font-black text-slate-500">
								Daftar
							</div>
							<div class="text-sm text-slate-400 max-w-sm">
								Bergabung dengan kami untuk mendapatkan akses tambahan
							</div>
						</div>
						
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-lg">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
START TRANSACTION;
INSERT INTO <code class="text-green-400">pengguna</code> 
  (<code class="text-pink-400">nama</code>, <code class="text-pink-400">jenis_kelamin</code>, <code class="text-pink-400">tanggal_lahir</code>, <code class="text-pink-400">email</code>, <code class="text-pink-400">kata_sandi</code>) 
  VALUE 
  (<code class="text-orange-400">'Edd'</code>, <code class="text-orange-400">'l'</code>, <code class="text-orange-400">'2020-01-01'</code>, <code class="text-orange-400">'pengguna@gmail.com'</code>, <code class="text-blue-400">MD5</code>(<code class="text-orange-400">'password'</code>));
COMMIT;
</pre>
						</div>
					</div>
						<div class="p-6 grid md:grid-cols-2 grid-cols-1 gap-3">
							<div class="mb-2">
								<label for="nama" class="text-sm text-slate-500">
									Nama
								</label>
								<input type="text" id="nama" name="nama" class="px-3 py-2 border rounded-md w-full" placeholder="Nama" value="Edd">
							</div>
							<div class="mb-2">
								<label for="jenis_kelamin" class="text-sm text-slate-500">
									Jenis Kelamin
								</label>
								<select id="jenis_kelamin" name="jenis_kelamin" class="px-3 py-2 border rounded-md w-full">
									<option value="l">Laki-Laki</option>
									<option value="p">Perempuan</option>
								</select>
							</div>
							<div class="mb-2">
								<label for="tanggal_lahir" class="text-sm text-slate-500">
									Tanggal Lahir
								</label>
								<input type="date" id="tanggal_lahir" name="tanggal_lahir" class="px-3 py-2 border rounded-md w-full" value="2020-01-01">
							</div>
						</div>
						<div class="px-6 grid md:grid-cols-2 grid-cols-1 gap-3">
							<div class="mb-2">
								<label for="email" class="text-sm text-slate-500">
									Email
								</label>
								<input type="email" id="email" name="email" class="px-3 py-2 border rounded-md w-full" placeholder="Email" value="pengguna@gmail.com">
							</div>
							<div class="mb-2">
								<label for="kata_sandi" class="text-sm text-slate-500">
									Password
								</label>
								<input type="password" id="kata_sandi" name="kata_sandi" class="px-3 py-2 border rounded-md w-full" placeholder="Kata Sandi" value="password">
							</div>
						</div>
						<div class="p-6">
							<div class="mb-2">
								<div class="text-sm text-slate-400 max-w-sm">
									Sudah punya akun? <a href="/login" class="text-teal-600 decoration-dotted decoration-teal-600">Masuk</a> menggunakan akun Anda!
								</div>
							</div>
							<div class="pt-6">
								<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400" type="submit">
									Daftar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

</html>