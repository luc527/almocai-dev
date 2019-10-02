<?php
$root_path = "../../";
require_once($root_path . "classes/UsuarioDao.class.php");
require_once($root_path . "classes/CarneDao.class.php");
require_once($root_path . "classes/AlimentacaoDao.class.php");
require_once($root_path . "classes/FrequenciaDao.class.php");

include($root_path . 'valida_secao.php');
valida_secao_tipo($root_path, 'ALUNO');
$usuario = UsuarioDao::SelectPorMatricula($_SESSION['matricula']);
$usuario = UsuarioDao::SelectFrequencia($usuario);
$usuario = UsuarioDao::SelectCarnes($usuario);
$usuario = UsuarioDao::SelectAlimentacao($usuario);

require_once($root_path . "classes/UsuarioDao.class.php");

// Carrega o template geral
$perfil = file_get_contents($root_path . "template.html");

// Título da página
$title = "Perfil";
$perfil = str_replace("{title}", $title, $perfil);

// Vazios (peso_fonte)
$perfil = str_replace("{peso_fonte}", "", $perfil);

// <NAV>
$nav = file_get_contents($root_path . "componentes/nav-transparent.html");
$perfil = str_replace("{{nav}}", $nav, $perfil);

// <FOOTER>
$footer = file_get_contents($root_path . "componentes/footer.html");
$perfil = str_replace("{{footer}}", $footer, $perfil);

// Scripts
$scripts = file_get_contents("scripts.js");
$perfil = str_replace("{{scripts}}", $scripts, $perfil);

// <MAIN>
$main = file_get_contents("main.html");
$perfil = str_replace("{{main}}", $main, $perfil);



/**
 * CARTÕES
 */

/**
 * function gerarCartao(): gera os cartões com checkboxes ou radios cujos itens
 * 
 * vêm de uma tabela específica do BD
 * @param string $nomeArquivoCartao nome do arquivo HTML do cartão 
 * @param string @nomeArquivoItem nome do arquivo HTML do item do cartão
 * @param array $registros os registros a ser colocados como itens
 * @param mixed $idItensSelecionados o(s) iten(s) a aparecerem selecionados (os que o usuário já marcou)
 * 
 * @return string o componente cartão a ser carregado em main.html
 */
function gerarCartao(string $nomeArquivoCartao, string $nomeArquivoItem, $registros, $idItensSelecionados)
{
	if (!is_array($idItensSelecionados))
		$idItensSelecionados = array($idItensSelecionados);
	// Isso é feito para que esse cartão possa ser universal, isto é, possível de ser usado tanto em cartões que usam radio (única opção) quanto em cartões que usam checkbox (múltiplas opções)

	$itens = '';
	foreach ($registros as $registro) {
		$item = file_get_contents($nomeArquivoItem);
		$item = str_replace('{codigo}', $registro->getCodigo(), $item);
		$item = str_replace('{descricao}', $registro->getDescricao(), $item);
		$checked = "";
		if (in_array($registro->getCodigo(), $idItensSelecionados))
			$checked = " checked ";
		$item = str_replace('{checked}', $checked, $item);
		$itens .= $item;
	}
	$cartao = file_get_contents($nomeArquivoCartao);
	$cartao = str_replace('{{itens}}', $itens, $cartao);
	return $cartao;
}

// Cartão frequência
$checked = $usuario->getFrequencia()->getCodigo();
$cartao_freq = gerarCartao('cartao_frequencia.html', 'cartao_frequencia_item.html', FrequenciaDao::SelectTodas(), $checked);
$perfil = str_replace("{{cartao_frequencia}}", $cartao_freq, $perfil);

// Cartão intolerância
$intolerancia = file_get_contents("cartao_intolerancia.html");
$perfil = str_replace("{{cartao_intolerancia}}", $intolerancia, $perfil);

// Cartão carne
$id_carnes = array();
$carnes = $usuario->getCarnes();
foreach ($carnes as $carne) {
	array_push($id_carnes, $carne->getCodigo());
}
$checked = $id_carnes;
$cartao_carne = gerarCartao('cartao_carne.html', 'cartao_carne_item.html', CarneDao::SelectTodas(), $checked);
$perfil = str_replace("{{cartao_carne}}", $cartao_carne, $perfil);

// Cartão alimentação
$checked = $usuario->getAlimentacao()->getCodigo();
$cartao_alim = gerarCartao('cartao_alimentacao.html', 'cartao_alimentacao_item.html', AlimentacaoDao::SelectTodas(), $checked);
$perfil = str_replace("{{cartao_alimentacao}}", $cartao_alim, $perfil);

// Cartão alt_senha
$alt_senha = file_get_contents("cartao_alt_senha.html");
// Erro de senha antiga incorreta
$senha_antiga_incorreta = "";
if (isset($_GET['erro'])) {
	$senha_antiga_incorreta = $_GET['erro'] == 'senha_antiga_incorreta' ?
		file_get_contents('senha_antiga_incorreta.html')
		: "";
}
$alt_senha = str_replace("{{senha_antiga_incorreta}}", $senha_antiga_incorreta, $alt_senha);

$perfil = str_replace("{{cartao_alt_senha}}", $alt_senha, $perfil);



/**
 * A partir da matrícula na seção, consulta as informações do BD
 */
$perfil = str_replace("{nome}", $usuario->getNome(), $perfil); // Nome do usuário na página
$perfil = str_replace("{matricula}", $usuario->getCodigo(), $perfil); // Matricula do usuário


// Caminho à raiz do projeto -- sempre por último, antes do print
$perfil = str_replace("{root_path}", $root_path, $perfil);
// Renderiza página ao usuário
print($perfil);
