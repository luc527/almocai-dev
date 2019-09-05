<?php
include 'valida_secao.php';

if ($_SESSION['tipo'] != 1) {
  header("location:login.php");
} // se o usuário não é do tipo "adm", não poderá acessar essa página
?>
