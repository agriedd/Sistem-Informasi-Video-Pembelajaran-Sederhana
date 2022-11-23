<?php

	require_once "./sambungan.php";

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		$query = $sambungan->query("START TRANSACTION");
		$query->execute();

		$query_tambah_transaksi = "INSERT INTO transaksi (id_pembeli, tanggal) 
			VALUE (:id_pembeli, :tanggal)";
		$query = $sambungan->prepare($query_tambah_transaksi);

		$hasil = $query->execute([
			'id_pembeli' 	=> $_POST['id_pembeli'],
			'tanggal' 		=> $_POST['tanggal'],
		]);

		$query_mengambil_id_transaksi_terakhir = "SELECT id_transaksi FROM transaksi
			WHERE id_pembeli = :id_pembeli AND DATE(tanggal) = DATE(:tanggal)
			ORDER BY tanggal DESC
			LIMIT 1";
		$query = $sambungan->prepare($query_mengambil_id_transaksi_terakhir);
		$query->bindValue(':id_pembeli', $_POST['id_pembeli']);
		$query->bindValue(':tanggal', $_POST['tanggal']);
		$query->execute();
		$transaksi = $query->fetchObject();
		$id_transaksi = $transaksi->id_transaksi;

		foreach($_POST['id_barang'] as $index => $id_barang ){
			$query_tambah_pembelian_barang = "INSERT INTO pembelian (id_barang, id_transaksi, jumlah, tanggal)
				VALUE (:id_barang, :id_transaksi, :jumlah, :tanggal)";
			$query = $sambungan->prepare($query_tambah_pembelian_barang);
			$hasil = $query->execute([
				'id_barang' 	=> $id_barang,
				'id_transaksi' 	=> $id_transaksi,
				'jumlah' 		=> $_POST['jumlah'][$index],
				'tanggal'		=> $_POST['tanggal']
			]);
			if(!$hasil){
				break;
			}
			$query_update_stok_barang = "UPDATE barang SET stok = stok - :jumlah 
				WHERE id_barang = :id_barang
				LIMIT 1";
			$query = $sambungan->prepare($query_update_stok_barang);
			$hasil = $query->execute([
				'jumlah'		=> $_POST['jumlah'][$index],
				'id_barang'		=> $id_barang
			]);
			if(!$hasil){
				break;
			}
		}


		if($hasil){

			$query = $sambungan->query("COMMIT");
			$query->execute();
			
			header("location: ./transaksi.php?sukses=1");
			exit();
		} else {
			$query = $sambungan->query("ROLLBACK");
			$query->execute();
			header("location: ./tambah-transaksi.php?gagal=1");
			exit();
		}
	}

	$id_pembeli = null;
	$nama_pembeli = null;
	$jumlah_kolom = 1;
	if(isset($_GET['jumlah'])){
		$jumlah_kolom = (int) $_GET['jumlah'];
	}

	if(isset($_GET['nama'])){
		$nama = $_GET['nama'];
		$query_mencari_pembeli_berdasarkan_nama = "SELECT id_pembeli, nama FROM pembeli 
			WHERE nama LIKE :nama LIMIT 1";
		$query = $sambungan->prepare($query_mencari_pembeli_berdasarkan_nama);
		$query->bindValue(':nama', "%{$nama}%", PDO::PARAM_STR);
		$query->execute();
		$pembeli = $query->fetchObject();
		/**
		 * jika ada
		 * 
		 */
		if($query->rowCount()){
			$id_pembeli = $pembeli->id_pembeli;
			$nama_pembeli = $pembeli->nama;
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
			<div class="text-sm text-slate-400">Tambah</div>
			<h1 class="text-6xl font-bold font-serif mb-4 text-slate-700">Transaksi</h1>
			<div class="font-thin">
				<p>
					Berikut merupakan query dalam menambah sebuah <i>record</i> baru pada tabel
					<code class="rounded-md border-b-2 bg-slate-100 border-slate-300 text-slate-500 text-sm px-2">transaksi</code>
				</p>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
INSERT INTO <code class="text-green-400">transaksi</code> (id_pembeli, tanggal) 
VALUE (<code class="text-pink-400">1</code>, <code class="text-yellow-600">'2022-10-25'</code>);</pre>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
INSERT INTO <code class="text-green-400">pembelian</code> (id_barang, id_transaksi, jumlah, tanggal)
VALUE (<code class="text-pink-400">1</code>, <code class="text-pink-400">1</code>, <code class="text-pink-400">10</code>, <code class="text-yellow-600">'2022-10-25'</code>);</pre>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
UPDATE <code class="text-green-400">barang</code> SET stok = stok - <code class="text-pink-400">2</code> 
WHERE id_barang = <code class="text-pink-400">1</code>
LIMIT <code class="text-pink-400">1</code>;</pre>
			</div>
		</div>
		<div class="w-full bg-slate-50">
			<div class="mx-auto max-w-lg">
				<form action="" method="GET">
					<table class="w-full">
						<tbody>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									Jumlah Barang
								</td>
								<td class="p-3">
									<input type="text" name="jumlah" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Jumlah kolom input barang" value="<?php echo $_GET['jumlah'] ?? 1 ?>">
									<input type="hidden" name="nama" value="<?php echo $_GET['nama'] ?? null ?>">
								</td>
								<td class="p-3">
									<button type="submit" class="px-5 py-2 rounded-lg border-b-4 border-slate-400 text-slate-600 bg-slate-200 hover:bg-slate-300 active:border-b-0 transition-all duration-100 active:mt-1 text-sm">
										Tambah
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<div class="w-full bg-slate-50">
			<div class="mx-auto max-w-lg">
				<form action="" method="GET">
					<table class="w-full">
						<tbody>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									Pilih Pembeli
								</td>
								<td class="p-3">
									<input type="text" name="nama" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Masukan nama pembeli" value="<?php echo $_GET['nama'] ?? null ?>">
									<input type="hidden" name="jumlah" value="<?php echo $_GET['jumlah'] ?? 1 ?>">
								</td>
								<td class="p-3">
									<button type="submit" class="px-5 py-2 rounded-lg border-b-4 border-slate-400 text-slate-600 bg-slate-200 hover:bg-slate-300 active:border-b-0 transition-all duration-100 active:mt-1 text-sm">
										Temukan
									</button>
								</td>
							</tr>
							<?php
								if($nama_pembeli):
							?>
							<tr>
								<td>

								</td>
								<td colspan="2" class="p-3">
									<div class="text-sm text-slate-300">
										menemukan pembeli: <?php echo $nama_pembeli ?>
									</div>
								</td>
							</tr>
							<?php
								endif;
							?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<div class="w-full bg-slate-800">
			<div class="mx-auto max-w-lg">
				<pre class="text-slate-200 bg-slate-800 rounded-md p-3 text-sm font-mono">
SELECT id_pembeli, nama FROM <code class="text-green-400">pembeli</code> 
WHERE <code class="text-blue-400">nama</code> LIKE <code class="text-yellow-600">'%<?php echo $_GET['nama'] ?? 'John' ?>%'</code>
LIMIT 1;</pre>
			</div>
		</div>
		<div class="w-full bg-slate-50">
			<div class="mx-auto max-w-lg">
				<form action="" method="POST">
					<table class="w-full">
						<tbody>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									ID Pembeli
								</td>
								<td class="p-3" colspan="2">
									<input type="text" name="id_pembeli" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Contoh: 1" value="<?php echo $id_pembeli ?>">
								</td>
							</tr>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									Tanggal
								</td>
								<td class="p-3" colspan="2">
									<input type="date" name="tanggal" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full">
								</td>
							</tr>
							<?php 
								for($i = 0; $i < $jumlah_kolom; $i++):
							?>
							<tr>
								<td class="p-3 text-sm text-slate-400">
									ID Barang
								</td>
								<td class="p-3">
									<input type="text" name="id_barang[]" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full">
								</td>
								<td class="p-3">
									<input type="number" name="jumlah[]" class="px-3 py-2 border border-slate-200 rounded-md focus:border-slate-500 w-full" placeholder="Jumlah">
								</td>
							</tr>
							<?php 
								endfor;
							?>
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