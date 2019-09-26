<?php
$root_path = "../";
include($root_path."valida_secao.php");
valida_secao_tipo($root_path, 'FUNCIONARIO');
require_once($root_path."classes/DiaAlmocoDao.class.php");

date_default_timezone_set("America/Sao_Paulo");

$dia = DiaAlmocoDao::SelectPorData(date("Y-m-d"));

$func = file_get_contents($root_path."template.html");

$title = 'Página inicial - Funcionário';
$peso_fonte = ',200,700';
$nav = file_get_contents($root_path."componentes/nav-funcionario-trp.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents("scripts.js");

$func = str_replace("{title}", $title, $func);
$func = str_replace("{peso_fonte}", $peso_fonte, $func);
$func = str_replace("{{nav}}", $nav, $func);
$func = str_replace("{{footer}}", $footer, $func);
$func = str_replace("{{scripts}}", $title, $func);

/**
 * MAIN
 */
$main = file_get_contents("main.html");
// Valores e componentes
$data = date("d/m");
if (true) $erro_qtd_indef = "";
if (true) $erro_card_indisp = "";
// Cartão de contagem de presenças
$contagem = DiaAlmocoDao::ContagemPresencas($dia->getCodigo());
// Carrega valores e componentes no template
$main = str_replace("{data}", $data, $main);
$main = str_replace("{{erro_qtd_indefinida}}", $erro_qtd_indef, $main);
$main = str_replace("{{erro_cardapio_indisponivel}}", $erro_card_indisp, $main);
// Carrega as contagens de presenças
$main = str_replace("{qtd_almocos}", $contagem[0], $main);
$main = str_replace("{qtd_carnivoros}", $contagem[1], $main);
$main = str_replace("{qtd_vegetarianos}", $contagem[2], $main);
$main = str_replace("{qtd_veganos}", $contagem[3], $main);
// Carrega main na página
$func = str_replace("{{main}}", $main, $func);

$func = str_replace("{root_path}", $root_path, $func);
print($func);
?>