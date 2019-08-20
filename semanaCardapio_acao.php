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
	} else if ($acao == 'Deletar') {
		$codigo = $_GET['codigo'];

		$semana = new SemanaCardapio;
		$semana->setCodigo($codigo);
		$diaDao = new DiaAlmocoDao;
		$dias = $diaDao->SelectPorSemana($semana->getCodigo());
		$alimentoDao = new AlimentoDao;
		for ($i=0; $i < count($dias); $i++) { 
			$alimentos = $alimentoDao->SelectPorDia($dias[$i]->getCodigo());
			for ($j=0; $j < count($alimentos); $j++) { 
				$dias[$i]->setAlimento($alimentos[$j]);
			}
		}
		for ($i=0; $i < count($dias); $i++) { 
			$semana->setDia($dias[$i]);
		}

		$semanaDao = new SemanaCardapioDao;

		$semanaDao->Deletar($semana);
	}
}

header("location:semanaCardapio_list.php");
?>
