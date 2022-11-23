<?php

	$aktif = $aktif ?? "awal";
	function isNavbar($name)
	{
		global $aktif;
		return $aktif == $name ? "bg-white shadow-sm" : "";
	}

?>
<div class="flex justify-between p-3 w-full items-center max-w-full">
	<div class="px-3">
		<div>
			<div class="text-5xl font-black text-teal-500 drop-shadow-xl font-sans">
				V
			</div>
		</div>
	</div>
	<div class="p-1 rounded-full bg-slate-100">
		<ul class="flex gap-1 flex-nowrap overflow-x-auto">
			<li class="transition-all duration-75 flex-shrink-0 px-4 py-2 rounded-full bg-slate-800 text-slate-100 hover:text-slate-200">
				<a href="/" class="text-sm">
					Web
				</a>
			</li>
			<li class="transition-all duration-75 flex-shrink-0 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("admin") ?>">
				<a href="/admin" class="text-sm">
					Dashboard
				</a>
			</li>
			<li class="transition-all duration-75 flex-shrink-0 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("kelas") ?>">
				<a href="/admin/kelas" class="text-sm">
					Kelas
				</a>
			</li>
			<li class="transition-all duration-75 flex-shrink-0 px-4 py-2 rounded-full text-slate-600 hover:text-slate-400 <?= isNavbar("kuis") ?>">
				<a href="/admin/kuis" class="text-sm">
					Kuis
				</a>
			</li>
			<li class="transition-all duration-75 flex-shrink-0
							px-4 py-2 rounded-full text-slate-600
							hover:text-slate-400 <?= isNavbar("akun") ?>">
				<a href="./perancangan.php" class="text-sm">
					Akun
				</a>
			</li>
			<li class="transition-all duration-75 flex-shrink-0
							px-4 py-2 rounded-full text-slate-600
							hover:text-slate-400 <?= isNavbar("masuk") ?> <?= isNavbar("masuk-admin") ?>">
				<a href="/login" class="text-sm">
					Keluar
				</a>
			</li>
		</ul>
	</div>
	<div></div>
</div>