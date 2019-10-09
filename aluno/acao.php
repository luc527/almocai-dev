<?php

if (!isset($_POST['acao']) && !isset($_GET['acao'])) {
  header("location:index.php");
}

$root_path = "../";
include("{$root_path}valida_secao.php");
valida_secao_tipo($root_path, 'ALUNO');
require_once("{$root_path}classes/UsuarioDao.class.php");
require_once("{$root_path}classes/DiaAlmoco.class.php");
require_once("{$root_path}classes/DiaAlmocoDao.class.php");
require_once("{$root_path}classes/AlunoPresenca.class.php");

$acao = $_POST['acao'];

$dia_cod = isset($_POST['dia_cod']) ? $_POST['dia_cod'] : '';

$semana_cod = isset($_POST['semana_cod']) ? $_POST['semana_cod'] : '';

// Para qual página o usuário deve ser redirecionado após cadastrar a ação
$redir = $_POST['redir'];
if ($redir == 'cardapio') {
  $redir = "{$root_path}aluno/cardapio/";
} else if ($redir == 'index') {
  $redir = "{$root_path}aluno";
}

CadPresenca($acao, $dia_cod, $semana_cod);

header("location:{$redir}");


// FUNÇÕES


function CadPresenca ($acao, $dia_cod, $semana_cod) {
  $user = new Usuario;
  $user->setCodigo($_SESSION['matricula']);

  if ($dia_cod != '') {
    CadPresencaDia($acao, $dia_cod, $user);
  } else if ($semana_cod != '') {
    CadPresencasSemana($acao, $semana_cod, $user);
  }
}

function CadPresencaDia($acao, $dia_cod, $user) {
  $presenca = new AlunoPresenca;
  $presenca->setAluno($user);
  
       if ($acao == 'PresencaSim') $presenca->setPresenca(1);
  else if ($acao == 'PresencaNao') $presenca->setPresenca(0);

  $dia = new DiaAlmoco;
  $dia->setCodigo($dia_cod);
  $dia->setPresenca($presenca);

  DiaAlmocoDao::InserirPresencas($dia);
}

function CadPresencasSemana($acao, $semana_cod, $user) {
  $dias = DiaAlmocoDao::SelectPorSemana($semana_cod);

  // o código a seguir é executado porque a função CadPresencaDia é reutilizada aqui,
  // e os únicos valores de ação que ela aceita são PresencaSim e PresencaNao
       if ($acao == 'PresencaTodosSim') $acao = 'PresencaSim';
  else if ($acao == 'PresencaTodosNao') $acao = 'PresencaNao';

  foreach ($dias as $dia) {
    CadPresencaDia($acao, $dia->getCodigo(), $user);
  }
}
?>