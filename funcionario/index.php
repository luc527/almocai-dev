<?php
$root_path = "../";
// if ($_SESSION['tipo'] == FUNCIONARIO) ... 

// Template geral
$funcionario = file_get_contents($root_path."template.html");

// Title, nav, footer, scripts
$title = "Página inicial";
$funcionario = str_replace("{title}", $title, $funcionario);

$nav = file_get_contents($root_path . "componentes/nav-funcionario.html");
$funcionario = str_replace("{{nav}}", $nav, $funcionario);

$footer = file_get_contents($root_path . "componentes/footer.html");
$funcionario = str_replace("{{footer}}", $footer, $funcionario);

$scripts = file_get_contents("scripts.js");
$funcionario = str_replace("{{scripts}}", $scripts, $funcionario);

// Vazios (peso_fonte)
$funcionario = str_replace("{peso_fonte}", "", $funcionario);


// MAIN
$main = file_get_contents("main.html");
$funcionario = str_replace("{{main}}", $main, $funcionario);

// Quantidade de alunos no almoço
//$qtd_alunos = AlunoPresencaDao:: etc
$qtd_alunos = 76;
$funcionario = str_replace("{qtd_alunos}", $qtd_alunos, $funcionario);


// Caminho à raiz
$funcionario = str_replace("{root_path}", $root_path, $funcionario);

print($funcionario);
?>