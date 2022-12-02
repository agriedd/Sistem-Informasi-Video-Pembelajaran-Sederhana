<?php

session_start();

$username = "root";
$password = "";
$database_name = "video_pembelajaran";

try {
	$sambungan = new PDO("mysql:host=localhost", $username, $password);
	
	$query_menggunakan_database = "USE {$database_name}";
	
	$sambungan->query($query_menggunakan_database);
} catch (\Throwable $th) {
	header("Location: ./skema-db.php");
	exit;
}

if($sambungan == null)
	exit();