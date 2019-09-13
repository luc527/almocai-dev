<?php
// Cada página chama essa função, informando seu caminho à raiz como parâmetro
function valida_secao($root_path) {
  session_start();
  if(!isset($_SESSION['matricula'])) {
    header($root_path."entrar");
  }
}
?>
