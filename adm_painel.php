<!DOCTYPE html>
<?php
include 'valida_secao.php';
if ($_SESSION['tipo'] != 1) {
  header("login.php");
} // se o usuário não é do tipo "adm", não poderá acessar essa página
?>
<html>
  <head>
    <title>Painel do(a) Administrador(a)</title>
    <meta charset="utf-8">
  </head>

  <body>
    <a href="funcionario_cad.php">Cadastro de funcionários</a>
  </body>
</html>
