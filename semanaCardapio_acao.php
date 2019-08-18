<?php

require_once "autoload.php";

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

if(isset($acao)) {
	if ($acao == 'Inserir') {
		$semanaDao = new SemanaCardapioDao;
		$codigo = $semanaDao->SelectUltimoCod() + 1;

		$semana = new SemanaCardapio;
		$semana->setCodigo($codigo);
		$semana->setData_inicio($_POST['data_inicio']);

		$semanaDao->Inserir($semana);
	}
}

header("location:semanaCardapio_list.php");
?>
