<?php
$root_path = "../";
include('valida_secao.php');
valida_secao($root_path);
date_default_timezone_set("America/Sao_Paulo");
require_once($root_path."classes/UsuarioDao.class.php");
$user = UsuarioDao::SelectPorMatricula($_SESSION['matricula']);

$aluno = file_get_contents($root_path."template.html");

$peso_fonte = ",200";
$title = "Página inicial";
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents("scripts.js");

$aluno = str_replace("{peso_fonte}", $peso_fonte, $aluno);
$aluno = str_replace("{title}", $title, $aluno);
$aluno = str_replace("{{nav}}", $nav, $aluno);
$aluno = str_replace("{{footer}}", $nav, $aluno);
$aluno = str_replace("{{scripts}}", $scripts, $aluno);

// MAIN
$nome = $user->getNome();
$data_txt = date("d/m");
$data_form = date("Y-m-d");
$main = file_get_contents("main.html");
$main = str_replace("{nome}", $nome, $main);
$main = str_replace("{data_txt}", $data_txt, $main);
$main = str_replace("{data_form}", $data_form, $main);
// Carrega main na página inicial do aluno
$aluno = str_replace("{{main}}", $main, $aluno);


$aluno = str_replace("{root_path}", $root_path, $aluno);
print($aluno);
?>