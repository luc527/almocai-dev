<?php

if (!isset($_POST['acao'])) {
  header("location:index.php");
}

$root_path = "../";
include($root_path . "valida_secao.php");
valida_secao_tipo($root_path, 'ALUNO');
require_once($root_path . "classes/UsuarioDao.class.php");
require_once($root_path . "classes/DiaAlmocoDao.class.php");
require_once($root_path . "classes/AlunoPresenca.class.php");

$user = new Usuario;
$user->setCodigo($_SESSION['matricula']);

$presenca = new AlunoPresenca;
$presenca->setAluno($user);

$acao = $_POST['acao'];

switch ($acao) {
  case 'PresencaSim':
    $presenca->setPresenca(1);
    break;
  case 'PresencaNao':
    $presenca->setPresenca(0);
    break;
}

$dia = DiaAlmocoDao::SelectPorData($_POST['data']);
$dia->setPresenca($presenca);

DiaAlmocoDao::InserirPresencas($dia);

header("location:index.php");
?>