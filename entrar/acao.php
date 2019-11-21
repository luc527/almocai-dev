<?php
$root_path = "../";
require_once($root_path."classes/Usuario.class.php");
require_once($root_path."classes/UsuarioDao.class.php");

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'Login') {
  $usuario = new Usuario;
  $usuario->setUsername($_POST['username']);
  $usuario->setSenha(sha1($_POST['senha']));

  $login_info = UsuarioDao::Login($usuario);

  if ($login_info['acao'] == 'fazer_login') {
    session_start();
    $_SESSION['codigo'] = $login_info['codigo'];
    $_SESSION['username'] = $login_info['username'];
    $_SESSION['nome'] = $login_info['nome'];
    $_SESSION['tipo'] = $login_info['tipo'];
    
    switch ($_SESSION['tipo']) {
      case 'ALUNO':
        $redir = $root_path . "aluno";
        break;
      case 'FUNCIONARIO':
        $redir = $root_path . "funcionario";
        break;
      case 'ADMINISTRADOR':
        $redir = $root_path . "administrador";
        break;
    }
    header("location:".$redir);
  } else {
    header("location:".$root_path."entrar/?erro=".$login_info['acao']);
  }
}
?>
