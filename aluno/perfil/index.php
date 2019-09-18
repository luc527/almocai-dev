<?php
$root_path = "../../";
require_once($root_path."classes/Conexao.class.php");
require_once($root_path."classes/CarneDao.class.php");
require_once($root_path."classes/AlimentacaoDao.class.php");
require_once($root_path."classes/FrequenciaDao.class.php");

/** PROVISÓRIO (p/ testar) */
//include($root_path.'valida_secao.php');
//valida_secao($root_path);
$_SESSION['matricula'] = 2019;
/** */

require_once($root_path."classes/UsuarioDao.class.php");

// Carrega o template geral
$perfil = file_get_contents($root_path."template.html");

// Título da página
$title = "Perfil";
$perfil = str_replace("{title}", $title, $perfil);

// Vazios (peso_fonte)
$perfil = str_replace("{peso_fonte}", "", $perfil);

// <NAV>
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$perfil = str_replace("{{nav}}", $nav, $perfil);

// <FOOTER>
$footer = file_get_contents($root_path."componentes/footer.html");
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
 * vêm de uma tabela específica do BD
 * 
 * (essa função poderia ser mais abstrata ainda e poderia ser usada p/ qlqr coisa
 * com itens que são registros do BD -- listagens etc.)
 * 
 * Por exemplo:
 * $nomeArquivoCartao = 'cartao_alimentacao.html';
 * $nomeArquivoItem = 'cartao_alimentacao_item.html';
 * $registros = AlimentacaoDao::SelectTodas();
 * 
 * Retorna HTML a ser substituído em, por exemplo {{cartao_alimentacao}}
 */
function gerarCartao($nomeArquivoCartao, $nomeArquivoItem, $registros) {
	$itens = '';
	foreach ($registros as $registro) {
		$item = file_get_contents($nomeArquivoItem);
		$item = str_replace('{codigo}', $registro->getCodigo(), $item);
		$item = str_replace('{descricao}', $registro->getDescricao(), $item);
		// {checked} deve vir do usuário. Por enquanto fica vazio
		$item = str_replace('{checked}', "", $item);
		$itens .= $item;
	}
	$cartao = file_get_contents($nomeArquivoCartao);
	$cartao = str_replace('{{itens}}', $itens, $cartao);
	return $cartao;
}

// Cartão frequência
$cartao_freq = gerarCartao('cartao_frequencia.html', 'cartao_frequencia_item.html', FrequenciaDao::SelectTodas());
$perfil = str_replace("{{cartao_frequencia}}", $cartao_freq, $perfil);

// Cartão intolerância
$intolerancia = file_get_contents("cartao_intolerancia.html");
$perfil = str_replace("{{cartao_intolerancia}}", $intolerancia, $perfil);

// Cartão carne
$cartao_carne = gerarCartao('cartao_carne.html', 'cartao_carne_item.html', CarneDao::SelectTodas());
$perfil = str_replace("{{cartao_carne}}", $cartao_carne, $perfil);

// Cartão alimentação
$cartao_alim = gerarCartao('cartao_alimentacao.html', 'cartao_alimentacao_item.html', AlimentacaoDao::SelectTodas());
$perfil = str_replace("{{cartao_alimentacao}}", $cartao_alim, $perfil);

// Cartão alt_senha
$alt_senha = file_get_contents("cartao_alt_senha.html");
$perfil = str_replace("{{cartao_alt_senha}}", $alt_senha, $perfil);



/**
 * A partir da matrícula na seção, consulta as informações do BD
 */
$usuario = UsuarioDao::SelectPorMatricula($_SESSION['matricula']);
$perfil = str_replace("{nome}", $usuario->getNome(), $perfil); // Nome do usuário na página
$perfil = str_replace("{matricula}", $usuario->getCodigo(), $perfil); // Matricula do usuário


// Caminho à raiz do projeto -- sempre por último, antes do print
$perfil = str_replace("{root_path}", $root_path, $perfil);
// Renderiza página ao usuário
print ($perfil);
?>