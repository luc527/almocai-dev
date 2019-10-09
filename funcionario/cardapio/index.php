<?php

$root_path = "../../";

include $root_path . "valida_secao.php";
valida_secao_tipo($root_path, "FUNCIONARIO");

include $root_path . "config.php";
require_once $root_path . "classes/SemanaCardapio.class.php";
require_once $root_path . "classes/SemanaCardapioDao.class.php";
require_once $root_path . "classes/DiaAlmoco.class.php";
require_once $root_path . "classes/DiaAlmocoDao.class.php";

$title = 'Gerenciar cardápio - Almoçaí';
$peso_fonte = ",200,700";
$nav = file_get_contents($root_path . "componentes/nav-funcionario-trp.html");
$footer = file_get_contents($root_path . "componentes/footer.html");
$scripts = file_get_contents("scripts.js");



/**
 * MAIN
 */
// carrega semana conforme código ou data ou mostra erro de cardápio indisponível
if (isset($_GET['cod'])) {
	$cardapio = SemanaCardapioDao::SelectPorCodigo($_GET['cod']);
} else {
	$cardapio_existe = SemanaCardapioDao::SemanaExiste(date("Y-m-d"));
	$cardapio = $cardapio_existe ?
		SemanaCardapioDao::SelectPorData(date("Y-m-d"))
		: false;
}

if (!$cardapio) {

	$cardapio_indisponivel = file_get_contents('cardapio_indisponivel.html');
	$add_alimento_semana = "";
	$dias = "";
	
} else {

	$cardapio_indisponivel = ""; // vazio pois cardápio está disponível

	// formulário para adicionar alimento em toda a semana
	$semana_cod = $cardapio->getCodigo();
	$select_tipo = file_get_contents("select_tipo.html");

	$add_alimento_semana = file_get_contents("add_alimento_semana.html");

	$add_alimento_semana = str_replace("{{ select_tipo }}", $select_tipo, $add_alimento_semana);
	$add_alimento_semana = str_replace("{semana_cod}", $semana_cod, $add_alimento_semana);

	// carrega dias 
	$cardapio = SemanaCardapioDao::SelectDias($cardapio);

	// cartões dos dias
	$dias = ""; // variável onde os dias da semana serão concatenados

	foreach ($cardapio->getDias() as $dia) {

		$dia_codigo = $dia->getCodigo();
		$data = date("d/m", strtotime($dia->getData()));
		$dia_da_semana = $dia->getDiaSemana();
		$cor_texto = [
			'Segunda-feira' => '',
			'Terça-feira' => 'text-azul',
			'Quarta-feira' => 'text-amarelo',
			'Quinta-feira' => 'text-vermelho'
		];

		// carrega alimentos do dia
		$dia = DiaAlmocoDao::SelectAlimentos($dia);

		// mostra alimentos do dia / gera {itens}
		$itens = ""; // valor em que todos os itens do dia estarão concatenados

		foreach ($dia->getAlimentos() as $alimento) {

			$codigo = $alimento->getCodigo();
			$nome = $alimento->getDescricao();
			$tipo = $alimento->getTipo();

			// carrega valores do alimento em item.html
			$item = file_get_contents("item.html");
			$item = str_replace("{nome}", $nome, $item);
			$item = str_replace("{codigo}", $codigo, $item);

			// concatena
			$itens .= $item;
		}

		// carrega valores e componentes em dia.html
		$dia = file_get_contents("dia.html");
		$dia = str_replace("{dia_da_semana}", $dia_da_semana, $dia);
		$dia = str_replace("{data}", $data, $dia);
		$dia = str_replace("{cor_texto}", $cor_texto["$dia_da_semana"], $dia);
		$dia = str_replace("{codigo}", $dia_codigo, $dia);
		$dia = str_replace("{{ itens }}", $itens, $dia);
		$dia = str_replace("{{ select_tipo }}", $select_tipo, $dia);

		// concatena
		$dias .= $dia;
	}

	$intervalo_semana = date("d/m", strtotime($cardapio->getData_inicio())) . " a " . 
		date("d/m", strtotime($cardapio->getData_inicio() . " + 3 days"));
}



$main = file_get_contents("main.html");

$main = str_replace("{intervalo_semana}", $intervalo_semana, $main);
$main = str_replace("{{ add_alimento_semana }}", $add_alimento_semana, $main);
$main = str_replace("{{ dias }}", $dias, $main);
/**
 * FIM MAIN
 */



$gercard = file_get_contents($root_path . "template.html"); // gercard = gerenciar cardapio

$gercard = str_replace("{title}", $title, $gercard);
$gercard = str_replace("{peso_fonte}", $peso_fonte, $gercard);
$gercard = str_replace("{{nav}}", $nav, $gercard);
$gercard = str_replace("{{main}}", $main, $gercard);
$gercard = str_replace("{{footer}}", $footer, $gercard);
$gercard = str_replace("{{scripts}}", $scripts, $gercard);

$gercard = str_replace("{root_path}", $root_path, $gercard);
print $gercard;
