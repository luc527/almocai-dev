<?php
$root_path = "../";
require_once($root_path."classes/Usuario.class.php");
require_once($root_path."classes/UsuarioDao.class.php");

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'CadastrarUsuario') {
  // Popula objeto Usuario com dados do formulário
  $usuario = new Usuario;
  $usuario->setNome($_POST['nome']);
  $usuario->setCodigo($_POST['matricula']);
  $usuario->setSenha( sha1($_POST['senha']) );
  $usuario->setTipo("ALUNO");

  // Cadastra o usuário, por meio do objeto, no BD
  UsuarioDao::Insert($usuario);
  header("location:".$root_path."entrar/");
}
?>
