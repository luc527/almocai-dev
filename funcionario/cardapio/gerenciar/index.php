<?php
$root_path = "../../../";

include($root_path."valida_secao.php");
valida_secao_tipo($root_path, 'FUNCIONARIO');

date_default_timezone_set('America/Sao_Paulo');

require_once($root_path."classes/SemanaCardapio.class.php");
require_once($root_path."classes/SemanaCardapioDao.class.php");
require_once($root_path."classes/DiaAlmocoDao.class.php");

// GERenciamento de CARDápio
$gercard = file_get_contents($root_path."template.html");

// Valores e componentes do template
$scripts = "";
$peso_fonte = "";
$nav = file_get_contents($root_path."componentes/nav-funcionario.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$title = 'Gerenciar cardápio';
// Substituição no template
$gercard = str_replace("{{scripts}}", $scripts, $gercard);
$gercard = str_replace("{peso_fonte}", $peso_fonte, $gercard);
$gercard = str_replace("{{nav}}", $nav, $gercard);
$gercard = str_replace("{{footer}}", $footer, $gercard);
$gercard = str_replace("{title}", $title, $gercard);

/**
 * MAIN
 */
$main = file_get_contents("main.html");

$semana_existe = SemanaCardapioDao::SemanaExiste(date("Y-m-d"));
if (!$semana_existe) // Se a semana não existe
{
  // Mostra erro de cardápio indisponível
  $indisponivel = file_get_contents("cardapio_indisponivel.html");
  $main = str_replace("{{cardapio_indisponivel}}", $indisponivel, $main);

  // Deixa vazio os cartões de dias
  $main = str_replace("{{dias}}", "", $main);
}
else // Caso a semana exista
{
  // Gera o <main> normalmente
  $main = geraMain($main); 
  // (funções estão embaixo do print($gercard);)
}

$gercard = str_replace("{{main}}", $main, $gercard);

$gercard = str_replace("{root_path}", $root_path, $gercard);
print($gercard);

// /////////////////////////////////// //
// GERADORES DOS COMPONENTES DO <MAIN> //
// /////////////////////////////////// //

function geraMain($main) {

  // Deixa vazio o erro cardápio indisponível
  $main = str_replace("{{cardapio_indisponivel}}", "", $main);

  // Carrega semana pela data
  $semana = SemanaCardapioDao::SelectPorData(date("Y-m-d"));

  // Formulário de cadastro de alimento em todos os dias
  $add_alimento_semana = gerarAddAlimentosSemana($semana);

  // Gera cartões dos dias por objeto semana
  $dias = gerarDias($semana);

  // Carrega cartões e formulário em <main>
  $main = str_replace("{{dias}}", $dias, $main);
  $main = str_replace("{{add_alimento_semana}}", $add_alimento_semana, $main);

  return $main;
}



function gerarAddAlimentosSemana(SemanaCardapio $semana) {
  $semana_cod = $semana->getCodigo();

  // Valores e componentes do formulário
  $select_tipo = file_get_contents("select_tipo.html");
  $form = file_get_contents("add_alimento_semana.html");
  
  // Carrega valores no template
  $form = str_replace("{semana_cod}", $semana_cod, $form);
  $form = str_replace("{{select_tipo}}", $select_tipo, $form);

  return $form;
}




function gerarDias(SemanaCardapio $semana) {
  
  // Carrega os dias da semana
  $semana = SemanaCardapioDao::SelectDias($semana);

  // Variável em que todos os cartões dos dias serão concatenados (código HTML)
  $cartoes_dias = "";

  // Cria cada cartão do dia e concatena em $cartoes_dias
  $dias = $semana->getDias();
  foreach ($dias as $dia) {

    // Valores e componentes do cartão dia
    $dia_codigo = $dia->getCodigo();
    $dia_semana = $dia->getDiaSemana();
    $dia_data = date("d/m", strtotime($dia->getData()));
    $select_tipo = file_get_contents("select_tipo.html");
    
    // Gera itens / alimentos
    $alimentos = geraAlimentos($dia);

    // Carrega valores e alimentos no cartão do dia
    $cartao_dia = file_get_contents("dia.html");
    $cartao_dia = str_replace("{dia_data}", $dia_data, $cartao_dia);
    $cartao_dia = str_replace("{dia_semana}", $dia_semana, $cartao_dia);
    $cartao_dia = str_replace("{dia_codigo}", $dia_codigo, $cartao_dia);
    $cartao_dia = str_replace("{{select_tipo}}", $select_tipo, $cartao_dia);
    $cartao_dia = str_replace("{{itens}}", $alimentos, $cartao_dia);

    // Concatena
    $cartoes_dias .= $cartao_dia;
  }

  return $cartoes_dias;
}



function geraAlimentos (DiaAlmoco $dia) {
  
  // Carrega alimentos do BD
  $dia = DiaAlmocoDao::SelectAlimentos($dia);
  $alimentos = $dia->getAlimentos();

  // Variável em que serão concatenados todos os itens/alimentos de um dia
  $itens_alimentos = "";
  
  // Cria item de cada alimento e concatena em $itens_alimentos
  foreach ($alimentos as $alimento) {
  
    // Valores do item/alimento
    $codigo = $alimento->getCodigo();
    $nome = $alimento->getDescricao();
    $tipo = $alimento->getTipo();

    // Carrega valores no template
    $item_alimento = file_get_contents("alimento.html");
    $item_alimento = str_replace("{codigo}", $codigo, $item_alimento);
    $item_alimento = str_replace("{nome}", $nome, $item_alimento);
    $item_alimento = str_replace("{tipo}", $tipo, $item_alimento);

    // Concatena 
    $itens_alimentos .= $item_alimento;
  }

  return $itens_alimentos;
}
?>