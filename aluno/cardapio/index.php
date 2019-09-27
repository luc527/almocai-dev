<?php
$root_path = "../../";
include($root_path."valida_secao.php");
valida_secao_tipo($root_path, 'ALUNO');
require_once($root_path . "classes/UsuarioDao.class.php");
require_once($root_path . "classes/SemanaCardapioDao.class.php");
require_once($root_path . "classes/Funcoes.class.php");

date_default_timezone_set("America/Sao_Paulo");

// Carrega template geral
$cardapio = file_get_contents($root_path."template.html");

// Carrega main.html
$main = file_get_contents("main.html");
$cardapio = str_replace("{{main}}", $main, $cardapio);

// Título da página
$title = "Cardápio";
$cardapio = str_replace("{title}", $title, $cardapio);

// Carrega componentes/nav-transparent.html 
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$cardapio = str_replace("{{nav}}", $nav, $cardapio);

// Carrega componentes/footer.html 
$footer = file_get_contents($root_path."componentes/footer.html");
$cardapio = str_replace("{{footer}}", $footer, $cardapio);

// Carrega scripts.js
$scripts = file_get_contents("scripts.js");
$cardapio = str_replace("{{scripts}}", $scripts, $cardapio);

// Vazios (peso_fonte)
$cardapio = str_replace("{peso_fonte}", "", $cardapio);


/**
 * Carrega a semana/cardápio / os cartões de cada dia / os alimentos
 */
$dataHj = date("Y-m-d");
if (SemanaCardapioDao::SemanaExiste($dataHj)) {

  $data = Funcoes::CorrigeData($dataHj);

  // Pega a semana do BD a partir da data
  $semana = SemanaCardapioDao::SelectPorDia($data);

  // Pega os dias da semana do BD
  $semana = SemanaCardapioDao::SelectDias($semana);
  $dias = $semana->getDias();

  // Pega os alimentos de cada dia do BD
  for ($i = 0; $i < count($dias); $i++) {
    $dias[$i] = DiaAlmocoDao::SelectAlimentos($dias[$i]);
    $alimentos[$i] = $dias[$i]->getAlimentos();
  }

  // Cria cada <li> alimento </li>
  for ($i = 0; $i < count($alimentos); $i++) {
    for ($j = 0; $j < count($alimentos[$i]); $j++) {

      // Seleciona o ícone conforme o tipo de alimento
      $icon_alimento = "";
      switch ($alimentos[$i][$j]->getTipo()) {
        case 'CARNE':
          $icon_alimento = file_get_contents("icon_alimento.html");
          $icon_alimento = str_replace("{icon_alimento}", 'carne.svg', $icon_alimento);
          break;

        case 'VEGETARIANA':
        case 'VEGANA':
          $icon_alimento = file_get_contents("icon_alimento.html");
          $icon_alimento = str_replace("{icon_alimento}", 'folha.svg', $icon_alimento);
          break;
      }

      // Carrega o template <li>alimento</li> e coloca o nome e ícone do alimento
      $li = file_get_contents("alimento_li.html");
      $alimentos[$i][$j] = str_replace("{nome_alimento}", $alimentos[$i][$j]->getDescricao(), $li);
      $alimentos[$i][$j] = str_replace("{{icon_alimento}}", $icon_alimento, $alimentos[$i][$j]);
      // As variáveis que antes eram objetos tornam-se strings aqui
    }
  }

  // Cria cada cartão/dia
  for ($i = 0; $i < count($dias); $i++) {
    $data = $dias[$i]->getData();
    $diaSemana = $dias[$i]->getDiaSemana();

    // Presença do aluno no dia
    $pres = UsuarioDao::SelectPresenca($dias[$i]->getCodigo(), $_SESSION['matricula']);
    if ($pres == 1) {
      $fundo_cor = ' aluno__confirmado ';
      $cor_texto_presenca = 'cardapio__confirmacao--confirmado';
      $texto_presenca = "Almoçarei";
    } else {
      $fundo_cor = ' aluno__negado ';
      $cor_texto_presenca = 'cardapio__confirmacao--negado';
      $texto_presenca = "Não almoçarei";
    }

    $dia[$i] = file_get_contents("dia_cartao.html");
    $dia[$i] = str_replace("{dia_cod}", $dias[$i]->getCodigo(), $dia[$i]);
    $dia[$i] = str_replace("{fundo_cor}", $fundo_cor, $dia[$i]);
    $dia[$i] = str_replace("{cor_texto_presenca}", $cor_texto_presenca, $dia[$i]);
    $dia[$i] = str_replace("{texto_presenca}", $texto_presenca, $dia[$i]);


    $alimentosHTML = "";

    // Concatena os <li>alimento</li> em um $alimentosHTML para cada dia
    for ($j = 0; $j < count($alimentos[$i]); $j++) {
      $alimentosHTML .= $alimentos[$i][$j];
    }

    // Carrega os valores e a lista de alimentos ao template dia_cartao.html
    $dia[$i] = str_replace("{{alimentos}}", $alimentosHTML, $dia[$i]);
    $dia[$i] = str_replace("{dia_semana}", $diaSemana, $dia[$i]);
    $dia[$i] = str_replace("{num_dia}", $i + 1, $dia[$i]);
  }

  // Cria o conjunto de cartões (concatena para {{dias_cartoes}} do main.html)
  $dias_cartoes = "";
  for ($i = 0; $i < count($dia); $i++) {
    $dias_cartoes .= $dia[$i];
  }
  /**
   * Fim do carregamento do cardápio
   */

  // Carrega os botões para marcar presença/ausência em todos os dias (+ código da semana p/ acao.php)
  $presenca_todos = file_get_contents("presenca_todos.html");
  $presenca_todos = str_replace("{semana_cod}", $semana->getCodigo(), $presenca_todos);

  // Não mostra erro de cardápio indisponível
  $cardapio_indisponivel = "";

  // Carrega período da semana
  $data_inic = date("d/m", strtotime($semana->getData_inicio()));
  $data_fim = date("d/m", strtotime($semana->getData_inicio() . ' + 3 days'));
  $periodo = $data_inic . " a " . $data_fim;

} else {

  $dias_cartoes = "";
  $presenca_todos = "";
  $cardapio_indisponivel = file_get_contents("cardapio_indisponivel.html");
  $periodo = "";
}

$cardapio = str_replace('{periodo_cardapio}', $periodo, $cardapio);
$cardapio = str_replace("{{dias_cartoes}}", $dias_cartoes, $cardapio);
$cardapio = str_replace("{{presenca_todos}}", $presenca_todos, $cardapio);
$cardapio = str_replace("{{cardapio_indisponivel}}", $cardapio_indisponivel, $cardapio);


// Carrega caminho à raiz (root_path) SEMPRE NO FINAL, ANTES DO PRINT
$cardapio = str_replace("{root_path}", $root_path, $cardapio);
print($cardapio);
?>