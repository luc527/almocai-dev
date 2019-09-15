<?php
$root_path = "../../";

// Carrega template geral
$cardapio = file_get_contents($root_path."template.html");

// Carrega main.html
$main = file_get_contents("main.html");
$cardapio = str_replace("{{main}}", $main, $cardapio);

// Título da página
$title = "Cardápio";
$cardapio = str_replace("{title}", $title, $cardapio);

// Carrega componentes/nav-transparent.html 
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$cardapio = str_replace("{{header}}", $nav, $cardapio);

// Carrega componentes/footer.html 
$footer = file_get_contents($root_path."componentes/footer.html");
$cardapio = str_replace("{{footer}}", $footer, $cardapio);

// Carrega scripts.js
$scripts = file_get_contents("scripts.js");
$cardapio = str_replace("{{scripts}}", $scripts, $cardapio);

// Vazios (peso_fonte)
$cardapio = str_replace("{peso_fonte}", "", $cardapio);

// Carrega caminho à raiz (root_path)
$cardapio = str_replace("{root_path}", $root_path, $cardapio);





?>