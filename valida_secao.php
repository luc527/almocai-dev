<?php
	include 'constantes.php';

	session_start();
	if (!isset($_SESSION['matricula'])) {
		header("location:login.php");
	}
?>
