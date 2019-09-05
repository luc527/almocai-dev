<?php

require_once 'autoload.php';

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

if (isset($acao)) {

	if ($acao == 'Inserir') {

		$alimento = new Alimento;
		$alimento->setDescricao($_POST['descricao']);
		$alimento->setTipo($_POST['tipo']);

		AlimentoDao::Inserir($alimento, $_POST['diaAlmoco_codigo']);

		header("location:semanaCardapio_cad+list.php");

	} else if ($acao == 'Deletar') {

		$codigo = $_GET['codigo'];

		$alimento = new Alimento;
		$alimento->setCodigo($codigo);

		AlimentoDao::Deletar($alimento);

		header("location:semanaCardapio_cad+list.php");

	}


}

?>
