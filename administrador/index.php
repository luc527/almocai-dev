<?php
$root_path = "../";

// Valida seção (inclusive para que só adminsitradores acessem o painel)
include($root_path."valida_secao.php");
valida_secao_tipo($root_path, 'ADMINISTRADOR');

// Valores a ser carregados no template {}
$title = 'Painel do administrador';
$root_path = $root_path;
$peso_fonte = ",200";
// Componentes HTML a ser carregados no template {{}}
$nav = file_get_contents($root_path."componentes/nav-administrador.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents('scripts.js');
// Componente main
$main = file_get_contents("main.html");

// Carregamento dos valores e componentes no template
$painel_adm = file_get_contents($root_path."template.html");
$painel_adm = str_replace("{title}", $title, $painel_adm);
$painel_adm = str_replace("{peso_fonte}", $peso_fonte, $painel_adm);
$painel_adm = str_replace("{{nav}}", $nav, $painel_adm);
$painel_adm = str_replace("{{footer}}", $footer, $painel_adm);
$painel_adm = str_replace("{{scripts}}", $scripts, $painel_adm);
$painel_adm = str_replace("{{main}}", $main, $painel_adm);

$painel_adm = str_replace("{root_path}", $root_path, $painel_adm);
print($painel_adm);
?>