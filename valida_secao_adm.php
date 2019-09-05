<?php
include 'valida_secao.php';

if ($_SESSION['tipo'] != ADMINISTRADOR) {
  header("location:login.php");
} // se o usuário não é do tipo "adm", não poderá acessar essa página
?>
