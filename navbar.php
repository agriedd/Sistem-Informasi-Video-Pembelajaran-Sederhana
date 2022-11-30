<?php

	$aktif = $aktif ?? "awal";
	function isNavbar($name)
	{
		global $aktif;
		return $aktif == $name ? "bg-white shadow-sm" : "";
	}

?>
<div class="flex justify-between p-3 w-full items-center">
	<div class="px-3">
		<div>
			<div class="text-5xl font-black text-teal-500 drop-shadow-xl font-sans">
				V
			</div>
		</div>
	</div>
	<div class="p-1 rounded-full bg-slate-200">
		<ul class="flex gap-1">
			<li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("awal") ?>">
				<a href="/" class="text-sm">
					Awal
				</a>
			</li>
			<!-- <li class="transition-all duration-75 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("perancangan") ?>">
				<a href="./perancangan.php" class="text-sm">
					Perancangan
				</a>
			</li> -->
			<li class="transition-all duration-75
							px-4 py-2 rounded-full text-slate-600
							hover:text-slate-400 <?= isNavbar("kelas") ?>">
				<a href="/kelas" class="text-sm">
					Kelas
				</a>
			</li>
			<?php
				if(isset($_SESSION['pengguna_aktif'])):
			?>
				<li class="transition-all duration-75
								px-4 py-2 rounded-full text-slate-600
								hover:text-slate-400 <?= isNavbar("masuk") ?> <?= isNavbar("masuk-admin") ?>">
					<a href="/pengguna" class="text-sm">
						Dasbor
					</a>
				</li>
				<li class="transition-all duration-75 flex-shrink-0
								px-4 py-2 rounded-full text-slate-600
								hover:text-slate-400 <?= isNavbar("masuk") ?> <?= isNavbar("masuk-admin") ?>">
					<a href="/logout" class="text-sm">
						Keluar
					</a>
				</li>
			<?php
				else: 
					if(isset($_SESSION['admin_aktif'])):
			?>
			<li class="transition-all duration-75
							px-4 py-2 rounded-full text-slate-600
							hover:text-slate-400 <?= isNavbar("masuk") ?> <?= isNavbar("masuk-admin") ?>">
				<a href="/admin" class="text-sm">
					Dasbor Admin
				</a>
			</li>
			<?php
					else: 
			?>
			<li class="transition-all duration-75
							px-4 py-2 rounded-full text-slate-600
							hover:text-slate-400 <?= isNavbar("masuk") ?> <?= isNavbar("masuk-admin") ?>">
				<a href="/login" class="text-sm">
					Masuk
				</a>
			</li>
			<?php
					endif;
				endif;
			?>
		</ul>
	</div>
	<div></div>
</div>