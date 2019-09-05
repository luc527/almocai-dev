<!DOCTYPE html>
<?php
  require_once('autoload.php');

  $acao = isset($_POST['acao']) ? $_POST['acao'] : '';
  if ($acao != '') {
    $usuario = new Usuario;
    $usuario->setCodigo($_POST['matricula']);
    $usuario->setSenha( sha1($_POST['senha']) );

    $login_info = UsuarioDao::Login_adm($usuario);

    if ($login_info[0] == 'infos_incorretas') {
      echo "<b>Erro: </b> As informações informadas estão incorretas.";
    } else if ($login_info[0] == 'fazer_login') {
      $_SESSION['matricula'] = $login_info[1];
      $_SESSION['nome'] = $login_info[2];
      $_SESSION['tipo'] = $login_info[3];

      header("location:adm_painel.php");
    }
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login do administrador</title>
  </head>

  <body>

    <div id='login-administrador'>
      <form action="" method="post"> <fieldset>
        <label for="matricula">Matrícula </label> <br/>
        <input type="text" name="matricula"> <br/><br/>

        <label for="senha">Senha</label> <br/>
        <input type="password" name="senha"> <br/><br/>

        <button type="submit" name="acao" value="login_adm">Login</button>
      </fieldset> </form>
    </div>

  </body>
</html>
