<?php

require_once "../sambungan.php";

if(!($_SESSION['admin_aktif'] ?? false)){
	header("Location: ../login/admin");
	exit();
}

$query_mengambil_record_admin = "SELECT email, nama FROM admin WHERE id_admin = :id_admin LIMIT 1";
$query = $sambungan->prepare($query_mengambil_record_admin);
$query->execute([
	'id_admin' => $_SESSION['admin_aktif']
]);

$admin = $query->fetchObject();

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
		require_once('./navbar.php');
		?>
		<div class="w-full border-b flex justify-center py-4 bg-pattern gap-4 p-4 flex-wrap">
			<div class="w-full max-w-2xl">
				<div class="bg-white rounded-md shadow-2xl shadow-slate-300">
					<div class="p-6 flex border-b">
						<div class="">
							<div class="text-4xl font-black text-slate-500">
								Dashboard
							</div>
							<div class="text-sm text-slate-400 group-hover:text-slate-500 max-w-sm">
								Anda dapat mengelola aplikasi sebagai admin
							</div>
						</div>
					</div>
					<div class="w-full bg-slate-800">
						<div class="mx-auto max-w-lg">
							<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT <code class="text-pink-400">email</code>, <code class="text-pink-400">nama</code> FROM <code class="text-green-400">admin</code>
	WHERE <code class="text-pink-400">id_admin</code> = <code class="text-orange-400"><?=$_SESSION['admin_aktif'] ?></code> LIMIT 1;</pre>
						</div>
					</div>
					<div class="p-4 flex gap-4">
						<div>
							<div class="flex flex-col justify-center h-full">
								<div class="bg-slate-200 rounded-md w-8 h-8">
		
								</div>
							</div>
						</div>
						<div class="">
							<div class="text-sm text-slate-600">
								<?=$admin->nama ?>
							</div>
							<div class="text-sm text-slate-300">
								<?=$admin->email ?>
							</div>
						</div>
					</div>
					<div class="">
						<ul class="grid grid-cols-1 divide-y divide-solid">
							<li class="">
								<a href="/admin/kelas/" class="p-6 flex h-full hover:bg-slate-50 group">
									<div class="text-2xl font-black text-slate-500 fill-slate-400 pr-4 ">
										<svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" class="bi bi-file-earmark-play" viewBox="0 0 16 16">
											<path d="M6 6.883v4.234a.5.5 0 0 0 .757.429l3.528-2.117a.5.5 0 0 0 0-.858L6.757 6.454a.5.5 0 0 0-.757.43z" />
											<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
										</svg>
									</div>
									<div>
										<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500">
											Kelas & Video
										</div>
										<div class="text-sm text-slate-300">
											Kelola Kelas dan Video Pembelajaran
										</div>
									</div>
								</a>
							</li>
							<li class="">
								<a href="/admin/kuis/" class="p-6 flex h-full hover:bg-slate-50 group">
									<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500 fill-slate-400 pr-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" class="bi bi-patch-question" viewBox="0 0 16 16">
											<path d="M8.05 9.6c.336 0 .504-.24.554-.627.04-.534.198-.815.847-1.26.673-.475 1.049-1.09 1.049-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.71 1.71 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745z" />
											<path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z" />
											<path d="M7.001 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0z" />
										</svg>
									</div>
									<div>
										<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500">
											Kuis & Pertanyaan
										</div>
										<div class="text-sm text-slate-300">
											Kelola kuis dan pertanyaan pada video pembelajaran
										</div>
									</div>
								</a>
							</li>
							<li class="">
								<a href="/admin/akun" class="p-6 flex h-full hover:bg-slate-50 group">
									<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500 fill-slate-400 pr-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" class="bi bi-person-circle" viewBox="0 0 16 16">
											<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
											<path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
										</svg>
									</div>
									<div>
										<div class="text-2xl font-black text-slate-400 group-hover:text-slate-500">
											Admin & Pengguna
										</div>
										<div class="text-sm text-slate-300">
											Kelola akun admin dan pengguna
										</div>
									</div>
								</a>
							</li>
						</ul>
					</div>
					<div class="p-6 border-t">
						<div class="">
							<a href="#" class="px-6 py-2 rounded-md text-sm border bg-red-100 text-red-700 border-red-100">
								Keluar
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>