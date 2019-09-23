<?php
$root_path = "../";
include($root_path."valida_secao.php");
valida_secao($root_path);
require_once($root_path."classes/DiaAlmocoDao.class.php");
require_once($root_path."classes/AlunoPresenca.class.php");

$acao = isset($_POST['acao']) ? $_POST['acao'] : '';

$aluno = new Aluno;
$aluno->setCodigo($_SESSION['matricula']);

$presenca = new AlunoPresenca;
$presenca->setAluno($aluno);

switch ($acao) {
  case 'PresencaSim':
    $presenca->setPresenca('1');
    break;
  case 'PresencaNao':
    $presenca->setPresenca('0');
    break;
}

$dia = DiaAlmocoDao::SelectPorData($_POST['data']);
$dia->setPresenca($presenca);

DiaAlmocoDao::InserirPresencas($dia);
header("location:".$root_path."aluno/");
?>