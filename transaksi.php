<?php

	require_once "./sambungan.php";

	
	$query_mengambil_record_transaksi = "SELECT 
		SUM(pembelian.jumlah * barang.harga) AS total
		FROM transaksi 
		LEFT JOIN pembelian 
			ON transaksi.id_transaksi = pembelian.id_transaksi
		LEFT JOIN barang 
			ON pembelian.id_barang = barang.id_barang
		WHERE DATE(NOW()) - INTERVAL 7 DAY <= DATE(transaksi.tanggal)";
	$query = $sambungan->query($query_mengambil_record_transaksi);
	$query->execute();
	$total_seminggu = $query->fetchObject();

	$query_mengambil_record_transaksi = "SELECT 
		transaksi.id_transaksi, 
		transaksi.tanggal, 
		barang.nama AS nama_barang, 
		pembelian.jumlah, 
		(pembelian.jumlah * barang.harga) AS total,
		pembeli.nama AS nama_pembeli 
		FROM transaksi 
		LEFT JOIN pembeli 
			ON transaksi.id_pembeli = pembeli.id_pembeli
		LEFT JOIN pembelian 
			ON transaksi.id_transaksi = pembelian.id_transaksi
		LEFT JOIN barang 
			ON pembelian.id_barang = barang.id_barang
		ORDER BY transaksi.tanggal DESC, transaksi.id_transaksi ASC";
	$query = $sambungan->query($query_mengambil_record_transaksi);


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
				<li class="active:translate-y-1 transition-all duration-75">
					<a href="./pembeli.php" class="px-4 py-3 rounded-lg border-b-4 border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 active:border-b-0 transition-all duration-100">
						Pembeli
					</a>
				</li>
				<li class="translate-y-1 transition-all duration-75">
					<a href="./transaksi.php" class="px-4 py-3 rounded-lg border-slate-300 text-slate-600 bg-slate-100 border-b-0 transition-all duration-100">
						Transaksi
					</a>
				</li>
			</ul>
		</div>
		<div class="max-w-lg p-3">
			<h1 class="text-6xl font-bold font-serif mb-4 text-slate-700">Transaksi</h1>
			<div class="font-thin">
				<p>
					Berikut merupakan query dalam menampilkan <i>record</i> tabel
					<code class="rounded-md border-b-2 bg-slate-100 border-slate-300 text-slate-500 text-sm px-2">transaksi</code>
				</p>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT 
	transaksi.id_transaksi, 
	transaksi.tanggal, 
	barang.nama AS nama_barang, 
	pembelian.jumlah, 
	(pembelian.jumlah * barang.harga) AS total,
	pembeli.nama AS nama_pembeli 
FROM <code class="text-green-400">transaksi</code> 
LEFT JOIN <code class="text-green-400">pembeli</code> 
	ON transaksi.id_pembeli = pembeli.id_pembeli
LEFT JOIN <code class="text-green-400">pembelian</code> 
	ON transaksi.id_transaksi = pembelian.id_transaksi
LEFT JOIN <code class="text-green-400">barang</code> 
	ON pembelian.id_barang = barang.id_barang
ORDER BY transaksi.tanggal DESC, transaksi.id_transaksi ASC;</pre>
			</div>
		</div>
		<div class="w-full">
			<div class="mx-auto max-w-lg">
				<div class="flex justify-end">
					<a href="./transaksi-tambah.php" class="px-4 py-3 rounded-lg border-b-4 border-slate-400 text-slate-600 bg-slate-200 hover:bg-slate-300 active:border-b-0 transition-all duration-100 active:translate-y-1 flex">
						Tambah Transaksi
					</a>
				</div>
			</div>
		</div>
		<div class="w-full">
			<div class="mx-auto max-w-5xl bg-slate-50 rounded-lg border border-slate-200">
				<table class="w-full">
					<thead>
						<tr>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								ID Transaksi
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								Tanggal
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								Nama Pembeli
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
								Barang
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-right">
								Jumlah
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-right">
								Total (Rp)
							</th>
							<th class="text-sm text-slate-500 p-3 border-b text-left">
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($query->rowCount()):
								while($transaksi = $query->fetchObject()):
						?>
						<tr class="group">
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $transaksi->id_transaksi ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $transaksi->tanggal ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $transaksi->nama_pembeli ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<?php echo $transaksi->nama_barang ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-right">
								<?php echo $transaksi->jumlah ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-right">
								<?php echo number_format($transaksi->total) ?>
							</td>
							<td class="p-3 text-sm text-slate-600 group-hover:bg-slate-800 group-hover:text-slate-50 border-b group-hover:border-pink-500 hover:bg-slate-200 transition-colors text-left">
								<!-- <a href="./pembelian-tambah?id_transaksi=<?php echo $transaksi->id_transaksi ?>" class="px-4 py-3 rounded-lg border-b-4 border-slate-400 text-slate-600 bg-slate-200 hover:bg-slate-300 active:border-b-0 transition-all duration-100 active:translate-y-1 flex">
									Lihat Daftar Tranas
								</a> -->
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
										<i>record</i> transaksi kosong
									</div>
									<div class="border-l p-4">
										<a href="./transaksi-tambah.php">
											Tambah transaksi
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
		<div class="w-full">
			<div class="mx-auto max-w-5xl p-3">
				<div class="flex justify-end gap-2 flex-col text-right">
					<div class="text-slate-500 text-sm">
						Total Transaksi 7 Hari Terakhir:
					</div>
					<div class="text-slate-700 text-5xl font-bold">
						Rp. <?php echo number_format($total_seminggu->total) ?>
					</div>
				</div>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT 
	SUM(pembelian.jumlah * barang.harga) AS total
FROM transaksi 
LEFT JOIN <code class="text-green-400">pembelian</code> 
	ON transaksi.id_transaksi = pembelian.id_transaksi
LEFT JOIN <code class="text-green-400">barang</code> 
	ON pembelian.id_barang = barang.id_barang
WHERE <code class="text-yellow-400">DATE</code>(<code class="text-yellow-400">NOW</code>()) - <code class="text-yellow-400">INTERVAL</code> <code class="text-pink-400">7 DAY</code> <= <code class="text-yellow-400">DATE</code>(transaksi.tanggal);</pre>
			</div>
		</div>
	</div>
</body>
</html>