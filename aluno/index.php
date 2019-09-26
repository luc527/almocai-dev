<?php
$root_path = "../";
include($root_path."valida_secao.php");
valida_secao($root_path);

require_once($root_path."classes/UsuarioDao.class.php");
require_once($root_path."classes/SemanaCardapioDao.class.php");
require_once($root_path."classes/DiaAlmocoDao.class.php");
require_once($root_path."classes/AlimentoDao.class.php");
require_once($root_path . "classes/Funcoes.class.php");
include($root_path.'config.php');

date_default_timezone_set("America/Sao_Paulo");

$usuario = UsuarioDao::SelectPorMatricula($_SESSION['matricula']);

// Carregando template
$aluno = file_get_contents($root_path."template.html");
$title = "Aluno";
$aluno = str_replace("{title}", $title, $aluno);
$peso_fonte = '';
$aluno = str_replace("{peso_fonte}", $peso_fonte, $aluno);
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$aluno = str_replace("{{nav}}", $nav, $aluno);
$footer = file_get_contents($root_path."componentes/footer.html");
$aluno = str_replace("{{footer}}", $footer, $aluno);
$scripts = '';
$aluno = str_replace("{{scripts}}", $scripts, $aluno);


$main = file_get_contents("main.html");
// Nome do usuário
$nome = $usuario->getNome();
$main = str_replace("{nome}", $nome, $main);

$datahj = date("d/m");
$data = date("Y-m-d");
if (SemanaCardapioDao::SemanaExiste($data)) {
  $cardapio_ind = ""; // não mostra erro de cardápio indisponível
  
  $dia = DiaAlmocoDao::SelectPorData(date("Y-m-d"));

  $cartao_presenca = file_get_contents("cartao_presenca.html");
  $cartao_presenca = str_replace("{data_hoje}", $datahj, $cartao_presenca);
  $cartao_presenca = str_replace("{dia_cod}", $dia->getCodigo(), $cartao_presenca);

  $alimentos = AlimentoDao::SelectPorDia($dia->getCodigo());
  $itens = "";
  foreach ($alimentos as $alimento) {
    $icon = "";
    switch ($alimento->getTipo()) {
      case 'CARNE':
        $icon = file_get_contents("cartao_dia_item_icon.html");
        $icon = str_replace("{icon}", 'carne', $icon);
        break;
      case 'VEGETARIANA':
      case 'VEGANA':
        $icon = file_get_contents("cartao_dia_item_icon.html");
        $icon = str_replace("{icon}", 'folha', $icon);
        break;
    }
    $item = file_get_contents("cartao_dia_item.html");
    $item = str_replace("{nome}", $alimento->getDescricao(), $item);
    $item = str_replace("{{icon}}", $icon, $item);
    $itens .= $item;
  }
  $cartao_dia = file_get_contents("cartao_dia.html");
  $cartao_dia = str_replace("{{itens}}", $itens, $cartao_dia);
  $dia_semana = $dia->getDiaSemana();
  $cartao_dia = str_replace("{dia_semana}", $dia_semana, $cartao_dia);
  $num_dia = $NUM_DIA[$dia->getDiaSemana()]; // array $NUM_DIA[] em config.php
  $cartao_dia = str_replace("{num_dia}", $num_dia, $cartao_dia);

  $pres = UsuarioDao::SelectPresenca($dia->getCodigo(), $usuario->getCodigo());
  if ($pres == 0) {
    $cor = ' vermelho ';
    $txt = 'Não almoçarei';
  } else {
    $cor = ''; // por padrão verde
    $txt = 'Almoçarei';
  }
  $Cpres_selec = file_get_contents("cartao_presenca_selecionada.html");
  $Cpres_selec = str_replace("{cor}", $cor, $Cpres_selec);
  $Cpres_selec = str_replace("{presenca_selecionada}", $txt, $Cpres_selec);
} else { // caso não exista a semana
  $cartao_dia = "";
  $cardapio_ind = file_get_contents("cardapio_indisponivel.html");
  $cartao_presenca = "";
  $Cpres_selec = "";
}
$main = str_replace("{{cartao_dia}}", $cartao_dia, $main);
$main = str_replace("{{cardapio_indisponivel}}", $cardapio_ind, $main);
$main = str_replace("{{cartao_presenca}}", $cartao_presenca, $main);
$main = str_replace("{{cartao_presenca_selecionada}}", $Cpres_selec, $main);

$aluno = str_replace("{{main}}", $main, $aluno);
$aluno = str_replace("{root_path}", $root_path, $aluno);
print($aluno);

//var_dump($dia);
?>