<?php
$root_path = "../../../";

//include($root_path."valida_secao.php");
//valida_secao($root_path);
$_SESSION['matricula'] = 2019;

// Template geral
$criar_card = file_get_contents($root_path."template.html");

// Título da página
$title = "Criar cardápio";
$criar_card = str_replace("{title}", $title, $criar_card);

// Vazios (peso_fonte, scripts)
$criar_card = str_replace("{{scripts}}", "", $criar_card);
$criar_card = str_replace("{peso_fonte}", "", $criar_card);

// <NAV>
$nav = file_get_contents($root_path."componentes/nav.html");
$criar_card = str_replace("{{nav}}", $nav, $criar_card);

// <FOOTER>
$footer = file_get_contents($root_path."componentes/footer.html");
$criar_card = str_replace("{{footer}}", $footer, $criar_card);

// <MAIN>
$main = file_get_contents("main.html");
$criar_card = str_replace("{{main}}", $main, $criar_card);


// Caminho à raiz -- sempre no final
$criar_card = str_replace("{root_path}", $root_path, $criar_card);

print($criar_card);
?>