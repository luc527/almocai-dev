<?php

require_once "autoload.php";

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

if(isset($acao)) {
	if ($acao == 'Inserir')	{
		$diaDao = new DiaAlmocoDao;
		$codigo = $diaDao->SelectUltimoCod() + 1;

		$diaSemana = new DiaSemana;
		$diaSemana->setCodigo($_POST['diaSemana_codigo']);

		// POPULAÇÃO OBJETO 'DiaAlmoco'
		$dia = new DiaAlmoco;
		$dia->setData($_POST['data']);
		$dia->setCodigo($codigo);
		$dia->setDiaSemana($diaSemana);

		// POPULAÇÃO OBJETO 'SemanaCardapio' COM O DIA
		$semana = new SemanaCardapio;
		$semana->setCodigo($_POST['semanaCardapio_codigo']);
		$semana->setDia($dia);

		$semanaDao = new SemanaCardapioDao;
		$semanaDao->InserirDias($semana);
	}
}

header("location:diaAlmoco_list.php");

?>