<?php

	require_once "./sambungan.php";

	$query_mengambil_record_pembeli = "SELECT * FROM pembeli";
	$query = $sambungan->query($query_mengambil_record_pembeli);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Basis Data Lanjutan</title>
	<script src="./tailwind.js"></script>
</head>
<body class="m-0 p-0">
	<div class="grid grid-cols-1 justify-items-center gap-4">
		<div class="max-w-lg p-3">
			<ul class="flex gap-2">
				<li class="active:translate-y-1 transition-all duration-75">
					<a href="./" class="px-4 py-3 rounded-lg border-b-4 border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 active:border-b-0 transition-all duration-100">
						Awal
					</a>
				</li>
				<li class="active:translate-y-1 transition-all duration-75">
					<a href="./barang.php" class="px-4 py-3 rounded-lg border-b-4 border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 active:border-b-0 transition-all duration-100">
						Barang
					</a>
				</li>
				<li class="translate-y-1 transition-all duration-75">
					<a href="./pembeli.php" class="px-4 py-3 rounded-lg border-slate-300 text-slate-600 bg-slate-100 border-b-0 transition-all duration-100">
						Pembeli
					</a>
				</li>
				<li class="active:translate-y-1 transition-all duration-75">
					<a href="./transaksi.php" class="px-4 py-3 rounded-lg border-b-4 border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 active:border-b-0 transition-all duration-100">
						Transaksi
					</a>
				</li>
			</ul>
		</div>
		<div class="max-w-lg p-3">
			<h1 class="text-6xl font-bold font-serif mb-4 text-slate-700">Pembeli</h1>
			<div class="font-thin">
				<p>
					Berikut merupakan query dalam menampilkan <i>record</i> tabel
					<code class="rounded-md border-b-2 bg-slate-100 border-slate-300 text-slate-500 text-sm px-2">pembeli</code>
				</p>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT * FROM <code class="text-green-400">pembeli</code>;</pre>
			</div>
		</div>
		<div class="w-full">
			<div class="mx-auto max-w-lg">
				<div class="flex justify-end">
					<a href="./pembeli-tambah.php" class="px-4 py-3 rounded-lg border-b-4 border-slate-400 text-slate-600 bg-slate-200 hover:bg-slate-300 active:border-b-0 transition-all duration-100 active:translate-y-1 flex">
						Tambah Pembeli
					</a>
				</div>
			</div>
		</div>
		<div class="w-full">
			<div class="mx-auto max-w-2xl bg-slate-50 rounded-lg border border-slate-200">
				<table class="w-full">
					<thead>
						<tr>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								ID Pembeli
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								Nama Pembeli
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								HP
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								Telepon
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								Alamat
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($query->rowCount()):
								while($pembeli = $query->fetchObject()):
						?>
						<tr class="group">
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $pembeli->id_pembeli ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $pembeli->nama ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $pembeli->hp ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $pembeli->telepon ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $pembeli->alamat ?>
							</td>
						</tr>
						<?php
								endwhile;
							else:
						?>
						<tr>
							<td colspan="4" class="p-3">
								<div class="flex w-full justify-center items-center border border-slate-200 text-slate-500">
									<div class="p-4">
										<i>record</i> pembeli kosong
									</div>
									<div class="border-l p-4">
										<a href="./pembeli-tambah.php">
											Tambah Pembeli
										</a>
									</div>
								</div>
							</td>
						</tr>
						<?php
							endif;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>