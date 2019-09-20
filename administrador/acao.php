<?php

$root_path = "../";

include($root_path."valida_secao.php");
valida_secao_adm($root_path);

require_once($root_path."classes/UsuarioDao.class.php");

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'Insert') {
  $usuario = new Usuario;
  $usuario->setCodigo( $_POST['matricula'] );
  $usuario->setNome( $_POST['nome'] );
  $usuario->setSenha( sha1($_POST['senha']) );
  $usuario->setTipo( $_POST['tipo'] );

  UsuarioDao::Insert($usuario);

  if ($_POST['tipo'] == 'ALUNO') $redir = $root_path."administrador/alunos/";
  else if ($_POST['tipo'] == 'FUNCIONARIO') $redir = $root_path."administrador/funcionarios/";
  header("location:".$redir);

} else if ($acao == 'Update') {
  $usuario = new Usuario;
  $usuario->setCodigo( $_POST['matricula'] );
  $usuario->setNome( $_POST['nome'] );
  
  UsuarioDao::UpdateNome($usuario);
  
  if ($_POST['tipo'] == 'ALUNO') $redir = $root_path . "administrador/alunos/";
  else if ($_POST['tipo'] == 'FUNCIONARIO') $redir = $root_path . "administrador/funcionarios/";
  header("location:" . $redir);

} else if ($acao == 'Delete') {
  UsuarioDao::Delete( $_GET['matricula'] );

  if ($_GET['tipo'] == 'ALUNO') $redir = $root_path . "administrador/alunos/";
  else if ($_GET['tipo'] == 'FUNCIONARIO') $redir = $root_path . "administrador/funcionarios/";
  header("location:" . $redir);
}



?>