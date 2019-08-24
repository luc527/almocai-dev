<?php

require_once('autoload.php');

if (isset($_POST['presenca'])) $presenca = $_POST['presenca'];
else if (isset($_GET['presenca'])) $presenca = $_GET['presenca'];
else $presenca = '';

$cod_semana = $_POST['cod_semana']; // apenas para redirecionamento

$aluno = new Aluno;
$aluno->setCodigo( $_POST['matricula'] );

$alunoPres = new AlunoPresenca;
$alunoPres->setAluno( $aluno );
$alunoPres->setPresenca( $presenca );

$diaAlmoco = new DiaAlmoco;
$diaAlmoco->setPresenca( $alunoPres );

DiaAlmocoDao::InserirPresencas($diaAlmoco);

header("location:semanaCardapio_view.php?codigo=".$cod_semana);

?>