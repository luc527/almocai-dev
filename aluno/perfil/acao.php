<?php
$root_path = "../../";

include($root_path.'valida_secao.php');
valida_secao($root_path);

require_once($root_path.'classes/UsuarioDao.class.php');


if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'AlterarSenha') {
  $senhaAntiga = sha1($_POST['senhaAntiga']);
  $senhaNova = sha1($_POST['senhaNova']);
  
  $usuario = UsuarioDao::SelectPorMatricula( $_SESSION['matricula'] );
  if ($usuario->getSenha() == $senhaAntiga) {
    $usuario->setSenha( $senhaNova );
    UsuarioDao::Update($usuario);
    header("location:".$root_path."aluno/perfil"); // + retorno de sucesso
  } else {
    // retornar erro ao usuário
  }
}
?>
