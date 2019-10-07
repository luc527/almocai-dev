<?php
$root_path = "../../";

include "{$root_path}valida_secao.php";
valida_secao_tipo($root_path, 'FUNCIONARIO');

require_once "{$root_path}classes/SemanaCardapio.class.php";
require_once "{$root_path}classes/SemanaCardapioDao.class.php";


$title = 'Cardápios';
$peso_fonte = ',200,700';
$nav = file_get_contents($root_path."componentes/nav-funcionario-trp.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents("scripts.js");

// Cria cartões das semanas
$semanas = SemanaCardapioDao::SelectTodos();
$cartoes = "";
foreach ($semanas as $semana) {
	$data_inicio = date("d/m", strtotime($semana->getData_inicio()));
	$data_fim = date("d/m", strtotime($semana->getData_inicio() . " + 3 days"));
	$semana_cod = $semana->getCodigo();

	$cartao = file_get_contents("cartao_semana.html");
	$cartao = str_replace("{data_inicio}", $data_inicio, $cartao);
	$cartao = str_replace("{data_fim}", $data_fim, $cartao);
	$cartao = str_replace("{semana_cod}", $semana_cod, $cartao);

	$cartoes .= $cartao;
}

$cardapios = file_get_contents("{$root_path}template.html");

$main = file_get_contents("main.html");
$main = str_replace("{{cartoes_semanas}}", $cartoes, $main);

$cardapios = str_replace("{title}", $title, $cardapios);
$cardapios = str_replace("{peso_fonte}", $peso_fonte, $cardapios);
$cardapios = str_replace("{{nav}}", $nav, $cardapios);
$cardapios = str_replace("{{main}}", $main, $cardapios);
$cardapios = str_replace("{{footer}}", $footer, $cardapios);
$cardapios = str_replace("{{scripts}}", $scripts, $cardapios);

$cardapios = str_replace("{root_path}", $root_path, $cardapios);
print($cardapios);