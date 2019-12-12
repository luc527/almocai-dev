<?php

/* Valida seção, requer classes e carrega informações do usuário ($usuario) */
require 'init.php';

/* Valores e componentes da página */
$title = 'Bem-vindo!';
$peso_fonte = $nav = $scripts = '';
$footer = file_get_contents($root_path.'componentes/footer.html');


/* Conteúdo da página */
// Gera formulário de frequência
$form_frequencia = gerarCartao(
	'form_frequencia.html',
	'form_frequencia_item.html',
	FrequenciaDao::SelectTodas(),
	0
);
$form_frequencia_msg = file_get_contents('frequencia_msg.html');
$form_frequencia = str_replace("{{frequencia_mensagem}}", $form_frequencia_msg, $form_frequencia);

// Gera formulário de alimentação
$form_alimentacao = gerarCartao(
	'form_alimentacao.html',
	'form_alimentacao_item.html',
	AlimentacaoDao::SelectTodas(),
	0
);


$main = file_get_contents('main.html');
$main = str_replace('{{form_frequencia}}', $form_frequencia, $main);
$main = str_replace('{{form_alimentacao}}', $form_alimentacao, $main);


/* Renderização da página */
$primeiro_login = file_get_contents($root_path.'template.html');
$primeiro_login = str_replace('{title}', $title, $primeiro_login);
$primeiro_login = str_replace('{peso_fonte}', $peso_fonte, $primeiro_login);
$primeiro_login = str_replace('{{nav}}', $nav, $primeiro_login);
$primeiro_login = str_replace('{{main}}', $main, $primeiro_login);
$primeiro_login = str_replace('{{footer}}', $footer, $primeiro_login);
$primeiro_login = str_replace('{{scripts}}', $scripts, $primeiro_login);

$primeiro_login = str_replace('{root_path}', $root_path, $primeiro_login);
print $primeiro_login;

