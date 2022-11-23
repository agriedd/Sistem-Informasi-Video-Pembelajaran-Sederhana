<?php

/**
 * menghapus database penjualan
 * 
 */

 /**
  * property sambungan database
  */
$username = "root";
$password = "";
$database_name = "video_pembelajaran";

$sambungan = new PDO("mysql:host=localhost", $username, $password);

$query_menghapus_database = "DROP DATABASE IF EXISTS {$database_name}";

$sambungan->query($query_menghapus_database);
echo "<kbd>Berhasil menghapus database</kbd>";

header("location: ./skema-db.php");