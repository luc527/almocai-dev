<?php
$root_path = "../../";
require_once($root_path."classes/Conexao.class.php");
require_once($root_path."classes/CarneDao.class.php");
require_once($root_path."classes/AlimentacaoDao.class.php");
require_once($root_path."classes/FrequenciaDao.class.php");

include($root_path.'valida_secao.php');
valida_secao_tipo($root_path, 'ALUNO');

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
 * Por exemplo:
 * $nomeArquivoCartao = 'cartao_alimentacao.html';
 * $nomeArquivoItem = 'cartao_alimentacao_item.html';
 * $registros = AlimentacaoDao::SelectTodas();
 * 
 * Retorna HTML a ser substituído em, por exemplo, {{cartao_alimentacao}}
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
/**
 * OBS: a frequência no BD só tem 2 valores, AUSENCIA ou PRESENCA
 * o BD irá, a partir delas, marcar automaticamente a semana de um aluno com presença
 * ou ela toda com ausência.
 * O aluno, no entanto, precisa ter mais opções no formulário
 * ("Sempre", "Geralmente", "Poucas vezes", "Nunca")
 * mesmo que algumas delas sejam registradas no BD do mesmo jeito
 */
for ($i=0; $i < 4; $i++) { 
	$freq[$i] = new Frequencia;
	switch ($i) {
		case 0:
			$freq[$i]->setDescricao("Sempre");	
			$freq[$i]->setCodigo(1); // PRESENCA
			break;		
		case 1:
			$freq[$i]->setDescricao("Geralmente");
			$freq[$i]->setCodigo(1); // PRESENCA
			break;
		case 2:
			$freq[$i]->setDescricao("Poucas vezes");
			$freq[$i]->setCodigo(0); // AUSENCIA
			break;
		case 3:
			$freq[$i]->setDescricao("Nunca");
			$freq[$i]->setCodigo(0); // AUSENCIA
			break;
	}
}
$cartao_freq = gerarCartao('cartao_frequencia.html', 'cartao_frequencia_item.html', $freq);
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