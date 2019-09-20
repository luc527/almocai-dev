<?php
// Cada página chama essa função, informando seu caminho à raiz como parâmetro
function valida_secao($root_path) {
  session_start();
  if(!isset($_SESSION['matricula'])) {
    header("location:" . $root_path . "entrar");
  }
}

function valida_secao_tipo ($root_path, $tipo) {
  valida_secao($root_path);
  if ($_SESSION['tipo'] != $tipo) {
    header("location:" . $root_path . "entrar");
  }
}
?>
