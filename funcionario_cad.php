<!DOCTYPE html>
<?php
  include 'valida_secao_adm.php';
?>
<html>
  <head>
    <title>Cadastro de funcionários(as)</title>
    <meta charset="utf-8">
  </head>

  <body>

    <form action="usuario_acao.php" method="post"> <fieldset>
      <label for="matricula">Matrícula</label> <br/>
      <input type="text" name="matricula"> <br/><br/>

      <label for="nome">Nome</label> <br/>
      <input type="text" name="nome"> <br/><br/>

      <label for="senha">Senha</label> <br/>
      <input type="password" name="senha"> <br/><br/>

      <input type="hidden" name="tipo" value="<?php echo FUNC; ?>">

      <button type="submit" name="acao" value="inserir">Cadastrar</button>
    </fiedset> </form>

  </body>
</html>
