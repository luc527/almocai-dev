<?php

$root_path = "../../";

require 'init.php';

// valores/componentes do template geral
$title = "Perfil";
$peso_fonte = "";
$nav = file_get_contents($root_path . "componentes/nav-transparent.html");
$footer = file_get_contents($root_path . "componentes/footer.html");
$scripts = file_get_contents("scripts.js");

// Alerta ao usuário caso não tenha e-mail cadastrado
$alerta_email = "";
$tem_email = strpos($usuario->getEmail(), '@');
if (!$tem_email) {
	$alerta_email = file_get_contents("alerta_email.html");
}

/**
 * Cartões (= configurações do usuário)
 */

// cartão de frequência (sempre almoça, poucas vezes almoça etc.)
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
	$freq_msg = file_get_contents("frequenciaAlterada_cartao.html");
	$freq_msg = str_replace("{mensagem}", $mensagem, $freq_msg);
}
$cartao_freq = str_replace("{{frequencia_mensagem}}", $freq_msg, $cartao_freq);

// cartão de alimentação (come carne, vegano, vegetariano...)
$cartao_alim = gerarCartao(
	'cartao_alimentacao.html',
	'cartao_alimentacao_item.html',
	AlimentacaoDao::SelectTodas(),
	$usuario->getAlimentacao()->getCodigo()
);

$cartao_carne = gerarCartaoCarne($usuario);

// TODO cartão que mostra as intolerâncias do usuário (não de cadastro)
$intolerancia = file_get_contents("cartao_intolerancia.html");

// cartão de alterar email
$alt_email = file_get_contents("cartao_alt_email.html");
$email = $usuario->getEmail() !== null ? $usuario->getEmail() : "";
$alt_email = str_replace("{email}", $email, $alt_email);

// mensagem caso e-mail tenha sido alterado com sucesso
$email_alterado = "";
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'email_alterado') {
	$email_alterado = file_get_contents("sucesso_email_alterado.html");
}
$alt_email = str_replace("{sucesso_email}", $email_alterado, $alt_email);


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
$main = str_replace("{username}", $usuario->getUsername(), $main);
$main = str_replace("{{alerta_email}}", $alerta_email, $main);
$main = str_replace("{{cartao_frequencia}}", $cartao_freq, $main);
$main = str_replace("{{cartao_intolerancia}}", $intolerancia, $main);
$main = str_replace("{{cartao_carne}}", $cartao_carne, $main);
$main = str_replace("{{cartao_alimentacao}}", $cartao_alim, $main);
$main = str_replace("{{cartao_alt_email}}", $alt_email, $main);
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
