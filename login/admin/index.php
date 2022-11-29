<?php

	require_once "../../sambungan.php";

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$query_validasi_login_admin = "SELECT id_admin, email FROM admin 
			WHERE email = :email AND kata_sandi = MD5(:kata_sandi) LIMIT 1";
		$query = $sambungan->prepare($query_validasi_login_admin);
		$hasil = $query->execute($_POST);

		/**
		 * jika record yang didapat melebihi 0 atau tidak 0
		 */
		if($query->rowCount() > 0){
			$admin = $query->fetchObject();
			$_SESSION['admin_aktif'] = $admin->id_admin;
			header("location: ../../admin");
			exit();
		} else {
			header("location: ./admin?gagal=1");
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
			$aktif = "masuk-admin";
			require_once('../../navbar.php');
		?>

		<div class="w-full border-b flex justify-center py-4 bg-pattern">
			<div class="w-full max-w-sm">
				<div class="text-2xl p-3 py-0 font-black text-gray-400 font-sans drop-shadow-md tracking-wide">
					<span class="text-teal-600">V</span>ideo
					Pembelajaran.
				</div>
				<div class="text-gray-400 text-sm px-3">
					Sistem informasi media pembelajaran gratis berbasis video.
				</div>
			</div>
		</div>
		<div class="w-full border-b flex justify-center py-4 bg-pattern">
			<div class="w-full max-w-sm">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-3 flex border-b">
						<div class="p-1 rounded-full bg-slate-100">
							<ul class="flex gap-1">
								<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("masuk") ?>">
									<a href="/login" class="text-sm">
										Pengguna
									</a>
								</li>
								<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("masuk-admin") ?>">
									<a href="/login/admin" class="text-sm">
										Admin
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-lg">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">id_admin</code>, <code class="text-pink-400">email</code> FROM <code class="text-green-400">admin</code>
	WHERE <code class="text-pink-400">email</code> = <code class="text-orange-400">'admin@vidpen.com'</code> 
	AND <code class="text-pink-400">kata_sandi</code> = <code class="text-blue-400">MD5</code>(<code class="text-orange-400">'password'</code>) LIMIT 1;</pre>
						</div>
					</div>
					<?php 
						if($_GET['gagal'] ?? '0' == 1):
					?>
					<div class="p-6 bg-red-50 text-red-600 text-sm border-b border-red-200">
						kombinasi email dan kata sandi tidak cocok dengan <i>record</i> mana pun.
					</div>
					<?php 
						endif;
					?>
					<form action="" method="POST">
						<div class="p-6">
							<div class="mb-6">
								<div class="text-4xl font-black text-slate-500">
									Masuk
								</div>
								<div class="text-sm text-slate-400 max-w-sm">
									Area admin, jika Anda bukan admin anda dapat <a href="/login" class="text-teal-600">
										ke halaman sebelumnya.
									</a> 
								</div>
							</div>
							<div class="mb-2">
								<label for="email" class="text-sm text-slate-500">
									Email
								</label>
								<input type="email" id="email" name="email" class="px-3 py-2 border rounded-md w-full" placeholder="Email" value="admin@vidpen.com">
							</div>
							<div class="mb-2">
								<label for="kata_sandi" class="text-sm text-slate-500">
									Password
								</label>
								<input type="password" id="kata_sandi" name="kata_sandi" class="px-3 py-2 border rounded-md w-full" placeholder="Kata Sandi" value="password">
							</div>
							<div class="pt-6">
								<button class="px-6 py-2 rounded-md border bg-teal-500 text-white border-teal-400" type="submit">
									Masuk
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