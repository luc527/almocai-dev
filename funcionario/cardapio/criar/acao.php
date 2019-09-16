<?php
$root_path = "../../../";
require_once($root_path."classes/SemanaCardapio.class.php");
require_once($root_path."classes/SemanaCardapioDao.class.php");

$acao = isset($_POST['acao']) ? $_POST['acao'] : '';

if ($acao == 'CriarCardapio') {
  $cardapio = new SemanaCardapio;
  $cardapio->setData_inicio($_POST['data_inicio']);

  SemanaCardapioDao::Inserir($cardapio);
  $cod = SemanaCardapioDao::SelectUltimoCod();
  header("location:".$root_path."funcionario/cardapio/gerenciar/?cod=".$cod);
}
?>