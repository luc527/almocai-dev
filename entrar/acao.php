<?php
$root_path = "../";
require_once($root_path."classes/Usuario.class.php");
require_once($root_path."classes/UsuarioDao.class.php");

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao != 'Login')
  header("location:{$root_path}");
  // única ação realizada nessa página página


$usuario = new Usuario;
$usuario->setUsername($_POST['username']);
$usuario->setSenha(sha1($_POST['senha']));

$login_info = UsuarioDao::Login($usuario);

if ($login_info['acao'] != 'fazer_login')
  header("location:{$root_path}entrar/?erro={$login_info['acao']}");


session_start();
$_SESSION['codigo'] = $login_info['codigo'];
$_SESSION['username'] = $login_info['username'];
$_SESSION['nome'] = $login_info['nome'];
$_SESSION['tipo'] = $login_info['tipo'];
$jaLogou = $login_info['jaLogou'];

$manterConectado = isset($_POST['manterConectado']);
if ($manterConectado) {
  $usuario->setCodigo($login_info['codigo']);
  
  $usuario->gerarToken();
  UsuarioDao::SalvarToken($usuario);

  $mesValidadeCookie = time() + (60 * 60 * 24 * 30);
  setcookie("almifctkn", $usuario->token(), $mesValidadeCookie, '/');
}

if (!$jaLogou && $login_info['tipo'] == 'ALUNO')
  header("location:{$root_path}aluno/primeiro_login");
else
  header("location:{$root_path}");
