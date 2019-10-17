<?php
$root_path = "../../";

include("{$root_path}valida_secao.php");
valida_secao($root_path);

require_once("{$root_path}classes/UsuarioDao.class.php");
require_once("{$root_path}classes/Frequencia.class.php");
require_once("{$root_path}classes/Carne.class.php");
require_once("{$root_path}classes/Alimentacao.class.php");

$usuario = new Usuario;
$usuario->setCodigo($_SESSION['matricula']);

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'AlterarSenha') {

  $senhaAntiga = sha1($_POST['senhaAntiga']);
  $_POST['senhaAntiga'] = '';

  $senhaNova = sha1($_POST['senhaNova']);
  $_POST['senhaNova'] = '';

  $usuario = UsuarioDao::SelectPorMatricula($usuario->getCodigo());
  if ($usuario->getSenha() == $senhaAntiga) {
    $usuario->setSenha($senhaNova);
    UsuarioDao::Update($usuario);
    session_destroy();
    header("location:{$root_path}entrar/?sucesso=senha_alterada");
  } else {
    header("location:{$root_path}aluno/perfil/?erro=senha_antiga_incorreta"); // retornar erro ao usuário
  }
} else if ($acao == 'SalvarFrequencia') {

  $frequencia = new Frequencia;
  $frequencia->setCodigo($_POST['frequencia']);
  $usuario->setFrequencia($frequencia);
  UsuarioDao::UpdateFrequencia($usuario);
  header("location:{$root_path}aluno/perfil/?freq_selecionada={$frequencia}");

} else if ($acao == 'SalvarCarnes') {

  $carnes_cod = $_POST['carnes'];
  for ($i = 0; $i < count($carnes_cod); $i++) {
    $carne[$i] = new Carne;
    $carne[$i]->setCodigo($carnes_cod[$i]);
    $usuario->setCarne($carne[$i]);
  }
  UsuarioDao::SalvarCarnes($usuario);
  header("location:{$root_path}aluno/perfil/");

} else if ($acao == 'SalvarAlimentacao') {

  $alimentacao = new Alimentacao;
  $alimentacao->setCodigo($_POST['alimentacao']);
  $usuario->setAlimentacao($alimentacao);
  UsuarioDao::UpdateAlimentacao($usuario);
  header("location:{$root_path}aluno/perfil/");
} else if ($acao == 'CadastrarIntolerancia') { } else if ($acao == 'RemoverIntrolerancia') { }
