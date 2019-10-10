<?php

// Inicia a seção, carrega arquivos necessários
// e alguns variáveis ($usuario, $root_path)
require 'bootstrap.php';

/**
 * Valores e componentes do template
 */
$title = 'Estatísticas';
$peso_fonte = '';
$scripts = '';
$nav = '';
$footer = file_get_contents("{$root_path}componentes/footer.html");

/**
 * Valores e componentes do <main>
 */
/**
 * Carrega valores e componentes em main.html
 */
$main = file_get_contents("main.html");


/**
 * Carrega valores e componentes dentro do template
 */
$estatisticas = file_get_contents("{$root_path}template.html");
$estatisticas = str_replace("{title}", $title, $estatisticas);
$estatisticas = str_replace("{peso_fonte}", $peso_fonte, $estatisticas);
$estatisticas = str_replace("{{nav}}", $nav, $estatisticas);
$estatisticas = str_replace("{{main}}", $main, $estatisticas);
$estatisticas = str_replace("{{footer}}", $footer, $estatisticas);
$estatisticas = str_replace("{{scripts}}", $scripts, $estatisticas);

$estatisticas = str_replace("{root_path}", $root_path, $estatisticas);
print($estatisticas);