<?php

require_once "autoload.php";

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];

if(isset($acao)) {
	if ($acao == 'Inserir') {
		$codigo = SemanaCardapioDao::SelectUltimoCod() + 1;

		$semana = new SemanaCardapio;
		$semana->setCodigo($codigo);
		$semana->setData_inicio($_POST['data_inicio']);

		SemanaCardapioDao::Inserir($semana);
	} else if ($acao == 'Deletar') {
		$codigo = $_GET['codigo'];

		$semana = new SemanaCardapio;
		$semana->setCodigo($codigo);
		$dias = DiaAlmocoDao::SelectPorSemana($semana->getCodigo());
		for ($i=0; $i < count($dias); $i++) { 
			$alimentos = AlimentoDao::SelectPorDia($dias[$i]->getCodigo());
			for ($j=0; $j < count($alimentos); $j++) { 
				$dias[$i]->setAlimento($alimentos[$j]);
			}
		}
		for ($i=0; $i < count($dias); $i++) { 
			$semana->setDia($dias[$i]);
		}

		SemanaCardapioDao::Deletar($semana);
	}
}

header("location:semanaCardapio_cad+list.php");
?>
