<?php
include('valida_secao.php');
valida_secao("");
if ($_SESSION['tipo' == 'ADMINISTRADOR']) {
	header("location:administrador/");
} else if ($_SESSION['tipo' == 'FUNCIONARIO']) {
	header("location:funcionario/");
} else if ($_SESSION['tipo' == 'ALUNO']) {
	header("location:aluno/");
}
?>