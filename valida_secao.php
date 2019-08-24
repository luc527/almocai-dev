<?php
	session_start();
	if (!isset($_SESSION['aluno_matricula']))
		header("location:login.php");
?>