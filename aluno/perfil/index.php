<?php

$root_path = "../../";

require 'bootstrap.php';

// valores/componentes do template geral
$title = "Perfil";
$peso_fonte = "";
$nav = file_get_contents($root_path . "componentes/nav-transparent.html");
$footer = file_get_contents($root_path . "componentes/footer.html");
$scripts = file_get_contents("scripts.js");

// cartões ( = configurações do usuário)
$cartao_freq = gerarCartao(
	'cartao_frequencia.html',
	'cartao_frequencia_item.html',
	FrequenciaDao::SelectTodas(),
	$usuario->getFrequencia()->getCodigo()
);

// cartão de retorno sobre frequência selecionada
$freq_msg = "";
if (isset($_GET['freq_selecionada'])) {
	if ($_GET['freq_selecionada'] == 1 || $_GET['freq_selecionada'] == 2) {
		$mensagem = "Conforme a frequência que você selecionou, o sistema automaticamente marcará presença em todos os dias.
		<br><b>Não se esqueça de marcar ausência nos dias que vier.</b>";
	} else { // 3 ou 4
		$mensagem = "Conforme a frequência que você selecionou, o sistema automaticamente marcará ausência em todos os dias.
		<br><b>Não se esqueça de marcar presença nos dias que vier.</b>";
	}
	$freq_msg = file_get_contents("frequencia_cartao.html");
	$freq_msg = str_replace("{mensagem}", $mensagem, $freq_msg);
}
$cartao_freq = str_replace("{{frequencia_mensagem}}", $freq_msg, $cartao_freq);


$cartao_alim = gerarCartao(
	'cartao_alimentacao.html',
	'cartao_alimentacao_item.html',
	AlimentacaoDao::SelectTodas(),
	$usuario->getAlimentacao()->getCodigo()
);
$cartao_carne = gerarCartaoCarne($usuario);
$intolerancia = file_get_contents("cartao_intolerancia.html");
// cartão de alterar senha + mostra erro caso senha antiga incorreta
$alt_senha = file_get_contents("cartao_alt_senha.html");
$senha_antiga_incorreta = "";
if (isset($_GET['erro']) && $_GET['erro'] == 'senha_antiga_incorreta') {
	$senha_antiga_incorreta = file_get_contents('senha_antiga_incorreta.html');
}
$alt_senha = str_replace("{{senha_antiga_incorreta}}", $senha_antiga_incorreta, $alt_senha);



// substituição dos valores/componentes nos templates
$main = file_get_contents("main.html");

$main = str_replace("{nome}", $usuario->getNome(), $main);
$main = str_replace("{matricula}", $usuario->getCodigo(), $main);
$main = str_replace("{{cartao_frequencia}}", $cartao_freq, $main);
$main = str_replace("{{cartao_intolerancia}}", $intolerancia, $main);
$main = str_replace("{{cartao_carne}}", $cartao_carne, $main);
$main = str_replace("{{cartao_alimentacao}}", $cartao_alim, $main);
$main = str_replace("{{cartao_alt_senha}}", $alt_senha, $main);

$perfil = file_get_contents($root_path . "template.html");

$perfil = str_replace("{title}", $title, $perfil);
$perfil = str_replace("{peso_fonte}", "", $perfil);
$perfil = str_replace("{{nav}}", $nav, $perfil);
$perfil = str_replace("{{footer}}", $footer, $perfil);
$perfil = str_replace("{{scripts}}", $scripts, $perfil);
$perfil = str_replace("{{main}}", $main, $perfil);

$perfil = str_replace("{root_path}", $root_path, $perfil);
print $perfil;
