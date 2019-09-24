<?php
$root_path = "../";
include($root_path."valida_secao.php");
valida_secao($root_path);

require_once($root_path."classes/UsuarioDao.class.php");
require_once($root_path."classes/DiaAlmoco.class.php");
require_once($root_path."classes/DiaAlmocoDao.class.php");
require_once($root_path."classes/AlimentoDao.class.php");
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

/**
 * Cartão do dia + cartão de confirmação + cardápio indisponível
 */
$dia = DiaAlmocoDao::SelectPorData( date("Y-m-d") );
if ($dia->getData() !== null) {
  // Cria o cartão de confirmação de presença
  $cartao_presenca = file_get_contents("cartao_presenca.html");
  $datahj = date("d/m");
  $cartao_presenca = str_replace("{data_hoje}", $datahj, $cartao_presenca);
  // Data para o cadastro da presença
  $data_bd = date("Y-m-d");
  $cartao_presenca = str_replace("{data_bd}", $data_bd, $cartao_presenca);
  // Carrega o cartão de cofirmação de presença em main
  $main = str_replace("{{cartao_presenca}}", $cartao_presenca, $main);

  // Deixa vazio o {{cardapio_indisponivel}}
  $main = str_replace("{{cardapio_indisponivel}}", '', $main);
  // Alimentos do dia
  $alimentos = AlimentoDao::SelectPorDia($dia->getCodigo());
  $itens = "";
  foreach ($alimentos as $alimento) {
    // Ícone a ser mostrado (ou não) ao lado do alimento
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
    // Carrega nome e ícone no item (alimento)
    $item = file_get_contents("cartao_dia_item.html");
    $item = str_replace("{nome}", $alimento->getDescricao(), $item);
    $item = str_replace("{{icon}}", $icon, $item);
    $itens .= $item;
  }
  $cartao_dia = file_get_contents("cartao_dia.html");
  // Carrega itens (alimentos) no cartão do dia
  $cartao_dia = str_replace("{{itens}}", $itens, $cartao_dia);
  // Carrega os outros componentes do cartao_dia
  $dia_semana = $dia->getDiaSemana();
  $cartao_dia = str_replace("{dia_semana}", $dia_semana, $cartao_dia);
  $num_dia = $NUM_DIA[$dia->getDiaSemana()];
  $cartao_dia = str_replace("{num_dia}", $num_dia, $cartao_dia);
} else {
  // Caso não exista um dia com a data informado
  // Também não existe a semana a que esse dia pertence (o BD cria os dias automaticamente)
  $cartao_dia = "";
  $cardapio_ind = file_get_contents("cardapio_indisponivel.html");
  $main = str_replace("{{cardapio_indisponivel}}", $cardapio_ind, $main);
  // Deixa vazio a confirmação de presença (dará erro se o usuário tentar marcar presença num dia que não existe)
  $main = str_replace("{{cartao_presenca}}", "", $main);
}
$main = str_replace("{{cartao_dia}}", $cartao_dia, $main);
/**
 * Fim cartão do dia
 */
 
// Cartão em que o aluno vê o que selecionou em relação a presença
$pres = UsuarioDao::SelectPresenca($dia->getCodigo() ,$usuario->getCodigo());
if ($pres == 0) {
  $cor = ' vermelho ';
  $txt = 'Não virei';
} else {
  $cor = ''; // por padrão verde
  $txt = 'Virei';
}
$Cpres_selec = file_get_contents("cartao_presenca_selecionada.html");
$Cpres_selec = str_replace("{cor}", $cor, $Cpres_selec);
$Cpres_selec = str_replace("{presenca_selecionada}", $txt, $Cpres_selec);
$main = str_replace("{{cartao_presenca_selecionada}}", $Cpres_selec, $main);

$aluno = str_replace("{{main}}", $main, $aluno);
$aluno = str_replace("{root_path}", $root_path, $aluno);
print($aluno);

//var_dump($dia);
?>