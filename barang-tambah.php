<?php

	require_once "./sambungan.php";

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$query_tambah_barang = "INSERT INTO barang (nama, stok, harga) VALUE (:nama, :stok, :harga)";
		$query = $sambungan->prepare($query_tambah_barang);
		$hasil = $query->execute($_POST);

		if($hasil){
			header("location: ./barang.php?sukses=1");
			exit();
		} else {
			header("location: ./tambah-barang.php?gagal=1");
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
				<li class="translate-y-1 transition-all duration-75">
					<a href="./barang.php" class="px-4 py-3 rounded-lg border-slate-300 text-slate-600 bg-slate-100 border-b-0 transition-all duration-100">
						Barang
					</a>
				</li>
				<li class="active:translate-y-1 transition-all duration-75">
					<a href="./pembeli.php" class="px-4 py-3 rounded-lg border-b-4 border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 active:border-b-0 transition-all duration-100">
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
			<div class="text-sm text-slate-400">Tambah</div>
			<h1 class="text-6xl font-bold font-serif mb-4 text-slate-700">Barang</h1>
			<div class="font-thin">
				<p>
					Berikut merupakan query dalam menambah sebuah <i>record</i> baru pada tabel
					<code class="rounded-md border-b-2 bg-slate-100 border-slate-300 text-slate-500 text-sm px-2">barang</code>
				</p>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
INSERT INTO <code class="text-green-400">barang</code> (nama, stok, harga) 
VALUE (<code class="text-yellow-600">'Pepsodent'</code>, <code class="text-pink-500">128</code>, <code class="text-pink-500">15000</code>);</pre>
			</div>
		</div>
		<div class="w-full bg-slate-50">
			<div class="mx-auto max-w-lg">
				<form action="" method="POST">
					<table class="w-full">
						<tbody>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									Nama Barang
								</td>
								<td class="p-3">
									<input type="text" name="nama" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Contoh: Pepsodent">
								</td>
							</tr>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									Stok Barang
								</td>
								<td class="p-3">
									<input type="number" name="stok" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Contoh: 128">
								</td>
							</tr>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									Harga Satuan (Rp)
								</td>
								<td class="p-3">
									<input type="number" name="harga" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Contoh: 15000">
								</td>
							</tr>
							<tr>
								<td class="p-3 text-sm text-slate-400">
								</td>
								<td class="p-3">
									<button type="submit" class="px-5 py-3 rounded-lg border-b-4 border-slate-400 text-slate-600 bg-slate-200 hover:bg-slate-300 active:border-b-0 transition-all duration-100 active:mt-1 text-sm">
										Tambah
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>