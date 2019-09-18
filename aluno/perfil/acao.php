<?php
$root_path = "../../";
//include($root_path.'valida_secao.php');
require_once($root_path.'classes/UsuarioDao.class.php');

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'AlterarSenha') {
  $senhaAntiga = $_POST['senhaAntiga'];
  $senhaNova = $_POST['senhaNova'];
  
  $usuario = new Usuario;
  $usuario->setTipo('ALUNO');
  $usuario->setCodigo( $_SESSION['matricula'] );
  //(bool) $verif_senha = UsuarioDao::VerifSenha($senhaAntiga, $_SESSION['matricula']);
  /*if ($verif_senha) {
    // altera a senha 
  } else {
    // retornar erro ao usuário: senha antiga não corresponde a senha no BD
  }*/
}
?>
