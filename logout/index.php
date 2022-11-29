<?php

	require_once "../sambungan.php";

	unset($_SESSION);
	session_reset();
	session_unset();
	header("location: ./login");
	exit();
?>