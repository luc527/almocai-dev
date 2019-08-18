<?php

require_once 'autoload.php';

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

if (isset($acao)) {

	if ($acao == 'Inserir') {

		$alimento = new Alimento;
		$alimento->setDescricao($_POST['descricao']);

		$alimentoDao = new AlimentoDao;
		$alimentoDao->Inserir($alimento, $_POST['diaAlmoco_codigo']);

		header("location:semanaCardapio_list.php");

	}

}

?>